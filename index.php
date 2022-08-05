<?php

use classes\Person;
use classes\PersonList;

define('BASE_PATH', dirname(realpath(__FILE__)) . '/');
include (BASE_PATH . 'helpers/autoload.php');

new Person(1, 'Nata', 'Kot', '01.12.2000', 1, 'Mozyr');
$person2 = new Person(22, 'Nata22', 'Kot22', '01.12.2022', 1, 'Minsk');
new Person(3, 'Nata3', 'Kot3', '01.12.2003', 0, 'Minsk');
$person33 = new Person(33, 'Nata33', 'Kot3', '01.12.2003', 0, 'Minsk');
$person36 = new Person(36, 'dsfd', 'sdsdf', '01.12.2006', 1, 'Mozyr');
echo 'person 36:<br>';
echo $person36;
$formatPerson = $person2->formatFields();
echo '<br><br>birthdayToAge:' . Person::birthdayToAge('12-12-2000') . '<br>';
echo '<br>genderToText:' . Person::genderToText(1) . '<br>';
echo '<br>formatPerson:<br>';
print_r($formatPerson);

echo '<br><br>persons list [3, 33, 35]:<br>';
echo new PersonList([3, 33, 35]);

echo '<br><br>persons list [1, 2, 3, 33, 35], city != Mozyr:<br>';
echo new PersonList([1, 2, 3, 33, 35], 'city', 'Mozyr', '!=');

echo '<br><br>persons list [3, 33, 35], city = Mozyr:<br>';
echo new PersonList([3, 33, 35], 'city', 'Mozyr', '=');

echo '<br><br>persons list [1, 2, 3, 33, 35], lastname > Kot2:<br>';
echo new PersonList([1, 2, 3, 33, 35], 'lastname', 'Kot2', '>');

echo '<br><br>persons list [1, 2, 3, 33, 35], gender = 1:<br>';
echo new PersonList([1, 2, 3, 33, 35], 'gender', '1', '=');

echo '<br><br>persons list [1, 2, 3, 33, 35], gender = 1, city = Mozyr:<br>';
echo new PersonList([1, 2, 3, 33, 35], ['gender', 'city'], ['1', 'Mozyr'], '=');
