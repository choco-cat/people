<?php

define('BASE_PATH', dirname(realpath(__FILE__)) . '/');
require BASE_PATH . 'lib/JsonDB.php';
require BASE_PATH . 'Person.php';
require BASE_PATH . 'PersonList.php';

$person = new Person(1, 'Nata', 'Kot', '01.12.2000', 1, 'Mozyr');
$person2 = new Person(2, 'Nata2', 'Kot2', '01.12.2002', 1, 'Minsk');
new Person(3, 'Nata3', 'Kot3', '01.12.2003', 0, 'Minsk');
$formatPerson = $person2->formatFields();
echo Person::birthdayToAge('12-12-2000') . '<br>';
echo Person::genderToText(1) . '<br>';
print_r($formatPerson);
$persons = new PersonList([1, 2]);
print_r($persons->get());
$persons->remove();
print_r($persons->get());

