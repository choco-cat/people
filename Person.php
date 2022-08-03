<?php

/**
 * Пример:
 * Класс Persone осуществляет сохранение полей пользователя, удаление
 * пользователя из БД, изменение полей.
 */
class Person
{
    public $id;
    public $firstname;
    public $lastname;
    public $birthday;
    public $gender;
    public $city;
    public $userArray;
    public $data;
    private $db;

    public function __construct($id, $firstname, $lastname, $birthday, $gender, $city)
    {
        $this->db = new JsonDB("./data/");
        $this->userArray = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birthday' => $birthday,
            'gender' => $gender,
            'city' => $city,
        );
        $selectResult = $this->db->select("users", "id", $id);
        if (count($selectResult) === 0) {
            $this->save();
        } else {
            $this->data = $selectResult[0];
        }
    }

    public function save()
    {
        $this->db->insert("users", $this->userArray, FALSE);
        $this->data = $this->userArray;
    }

    public function remove()
    {
        $this->db->delete("users", "id", $this->data['id']);
        $this->data = null;
    }

    public static function birthdayToAge($birthday)
    {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }

    public static function genderToText($gender)
    {
        return $gender === 0 ? 'муж' : 'жен';
    }
}
