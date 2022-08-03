<?php
define('BASE_PATH', dirname(realpath(__FILE__)) . '/');

require BASE_PATH . 'lib/JsonDB.php';
require BASE_PATH . 'Person.php';

$person = new Person(1, 'Nata', 'Kot', '01.12.2000', 1, 'Mozyr');
$person2 = new Person(2, 'Nata2', 'Kot2', '01.12.2002', 1, 'Minsk');

$person2->remove();

echo Person::birthdayToAge('12-12-2000');
echo Person::genderToText(1);