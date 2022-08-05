<?php

/**
 * Converts objects to array
 *
 * @param mixed $obj object(s)
 *
 * @return array|mixed
 */
function objToArray($obj)
{
    // Not an array or object? Return back what was given
    if (!is_array($obj) && !is_object($obj))
        return $obj;

    $arr = (array)$obj;

    foreach ($arr as $key => $value) {
        $arr[$key] = objToArray($value);
    }

    return $arr;
}
