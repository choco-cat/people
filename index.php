<?php

define('BASE_PATH', dirname(realpath(__FILE__)) . '/');
require BASE_PATH . 'lib/JsonDB.php';
require BASE_PATH . 'Person.php';
require BASE_PATH . 'PersonList.php';

$person = new Person(1, 'Nata', 'Kot', '01.12.2000', 1, 'Mozyr');
$person2 = new Person(2, 'Nata2', 'Kot2', '01.12.2002', 1, 'Minsk');
new Person(3, 'Nata3', 'Kot3', '01.12.2003', 0, 'Minsk');
$person33 = new Person(33, 'Nata33', 'Kot3', '01.12.2003', 0, 'Minsk');
$person34 = new Person(35, 'Nata34', 'Kot34', '01.12.2003', 0, 'Mozyr');
//$person33->remove();
$formatPerson = $person2->formatFields();
echo Person::birthdayToAge('12-12-2000') . '<br>';
echo Person::genderToText(1) . '<br>';
echo '<br>formatPerson:<br>';
print_r($formatPerson);

$persons = new PersonList([1, 2, 3, 33, 35], ['city', 'gender'], ['Mozyr', 0], '=');
echo '<br>persons list:<br>';
print_r($persons->get());
