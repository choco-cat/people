<?php

/**
 * Класс PersonList работает со списком пользователей, получает экзепляры класса Person,
 * удаляет пользователя с помощью класса Person.
 */

class PersonList
{
    public $personIds;
    private $db;

    public function __construct($personIds, $field = null, $fieldValue = null, $operator = null)
    {
        $this->db = new JsonDB('./data/');
        $this->personIds = $personIds;
        if ($field && $fieldValue) {
            foreach ($this->personIds as $key => $personId) {
                $searchResult = $this->db->select('users', ['id', $field], [$personId, $fieldValue], $operator);
                if (count($searchResult) === 0) {
                    unset($this->personIds[$key]);
                }
            }
        }
    }

    public function get()
    {
        $persons = array();
        foreach ($this->personIds as $personId) {
            $searchResult = $this->db->select('users', 'id', $personId);
            if (count($searchResult) > 0) {
                $personDB = $searchResult[0];
                $persons[] = new Person(
                    $personDB['id'],
                    $personDB['firstname'],
                    $personDB['lasname'],
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
}
