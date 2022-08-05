<?php

function getJsonChunk($fp, int $start_depth = -1)
{
    $bufsz = 8192;
    $start = false;
    $quotes = [false, false];
    $depth = 0;
    $totalBytesRead = 0;
    $end = false;
    $curPos = 0;

    if (!$fp) {
        return;
    }

    $curPos = ftell($fp);
    // Get total size of file
    fseek($fp, 0, SEEK_END);
    $size = ftell($fp);
    rewind($fp);

    while (!feof($fp)) {
        $buffer = fread($fp, $bufsz);
        $i = 0;

        if (false !== $buffer) {
            // So I do not have to do strlen to get read size which is linear
            $readCount = ($size > $bufsz) ? $bufsz : $size;
            $size -= $readCount;

            if (false === $start) {
                // Find first occurence of the curly bracket
                $start = strpos($buffer, '{');
                if (false === $start) {
                    $totalBytesRead += $readCount;
                    continue;
                } else {
                    $i = $start + 1;
                    $start += $totalBytesRead;
                    $totalBytesRead += $readCount;
                }
            } else {
                $totalBytesRead += $readCount;
            }

            for (; isset($buffer[$i]); $i++) {
                if ("'" == $buffer[$i] && !$quotes[1]) {
                    // If quote is escaped, ignore
                    if (!empty($buffer[$i - 1]) && '\\' == $buffer[$i - 1]) {
                        continue;
                    }

                    $quotes[0] = !$quotes[0];
                    continue;
                }

                if ('"' == $buffer[$i] && !$quotes[0]) {
                    // If quote is escaped, ignore
                    if (!empty($buffer[$i - 1]) && '\\' == $buffer[$i - 1]) {
                        continue;
                    }

                    $quotes[1] = !$quotes[1];
                    continue;
                }

                $is_quoted = in_array(true, $quotes, true);

                if ('{' == $buffer[$i] && !$is_quoted) {
                    if ($depth == $start_depth) {
                        $start = $totalBytesRead - $readCount + $i;
                    }
                    $depth++;
                }

                if ('}' == $buffer[$i] && !$is_quoted) {
                    $depth--;
                    if ($depth == $start_depth) {
                        $end = $totalBytesRead - $readCount + $i + 1;
                        break 2;
                    }
                }
            }
        }
    }

    $chunk = '';

    if (false !== $start && false !== $end) {
        fseek($fp, $start, SEEK_SET);
        $chunk = fread($fp, $end - $start);
    }

    fseek($fp, $curPos, SEEK_SET);

    return $chunk;
}
