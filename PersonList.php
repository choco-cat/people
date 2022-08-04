<?php

/**
 * Класс PersonList работает со списком пользователей, получает экзепляры класса Person,
 * удаляет пользователя с помощью класса Person.
 */

class PersonList
{
    public $personIds;
    private $db;

    //TODO: добавить операции сравнения
    public function __construct($personIds, $field = null, $fieldValue = null)
    {
        $this->db = new JsonDB('./data/');
        $this->personIds = $personIds;
        $searchResult = $field && $fieldValue ?
            $this->db->select('users', $field, $fieldValue)
            : $this->db->select('users');
        foreach ($searchResult as $person) {
            $this->personIds[] = $person['id'];
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
