<?php

/**
 * The PersonList class works with a list of users, receives instances of the Person class,
 * deletes the user using the Person class.
 */

namespace classes;

use lib\jsonDB;

if (!class_exists('\classes\Person')) {
    die('class Person not exists!');
}

class PersonList
{
    public $personIds;
    private $db;

    public function __construct($personIds, $field = null, $fieldValue = null, $operator = null)
    {
        $this->db = new JsonDB('./data/');;
        $this->personIds = $personIds;

        $resultArr = [];
        if ($field && $fieldValue) {
            foreach ($this->personIds as $personId) {

                if (is_array($field) && is_array($fieldValue)) {
                    $fieldArray = array();
                    $fieldArray[] = ['id', $personId, '='];
                    foreach ($field as $key => $f) {
                        $fieldArray[] = array($f, $fieldValue[$key], $operator);
                    }
                } else {
                    $fieldArray = [['id', $personId, '='], [$field, $fieldValue, $operator]];
                }
                $searchResult = $this->db->select('id')
                    ->from('users.json')
                    ->where($fieldArray, 'AND')
                    ->get();

                if (count($searchResult) > 0) {
                    $resultArr[] = $personId;
                }
            }
            $this->personIds = $resultArr;
        }
    }

    public function get()
    {
        $persons = array();
        foreach ($this->personIds as $personId) {
            $searchResult = $this->db->select('id')
                ->from('users.json')
                ->where(['id' => $personId])
                ->get();

            if (count($searchResult) > 0) {
                $personDB = $searchResult[0];
                $persons[] = new Person(
                    $personDB['id'],
                    $personDB['firstname'],
                    $personDB['lastname'],
                    $personDB['birthday'],
                    $personDB['gender'],
                    $personDB['city'],
                );
            }
        }
        return $persons;
    }

    public function remove()
    {
        $persons = $this->get();
        foreach ($persons as $person) {
            $person->remove();
        }
    }

    public function __toString()
    {
        $persons = $this->get();
        return implode('; ', $persons);
    }
}
