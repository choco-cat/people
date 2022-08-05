<?php
/**
 * Person class saves user fields, deletes
 * user from the database, changing fields.
 */

namespace classes;

use lib\jsonDB;

class Person
{
    public $id;
    public $firstname;
    public $lastname;
    public $birthday;
    public $gender;
    public $city;
    private $db;
    public $errors = [];

    public function __construct($id, $firstname, $lastname, $birthday, $gender, $city)
    {
        $this->db = new jsonDB('./data/');
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->city = $city;
        $selectResult = $this->db->select('*' )
            ->from( 'users.json' )
            ->where( [ 'id' => $id ] )
            ->get();

        if (count($selectResult) === 0) {
            $this->validate();
            if (!$this->errors) {
                $this->save();
            }

        } else {
            $this->id = $selectResult[0]['id'];
            $this->firstname = $selectResult[0]['firstname'];
            $this->lastname = $selectResult[0]['lastname'];
            $this->birthday = $selectResult[0]['birthday'];
            $this->gender = $selectResult[0]['gender'];
            $this->city = $selectResult[0]['city'];
        }
    }

    public function validate()
    {
        preg_match('/(?=.*[0-9])/i', $this->id, $matches);
        if (count($matches) === 0){
            $this->errors[] = 'id должен быть числовым!';
        }
        preg_match('/(?=.*[a-zа-яё])/i', $this->firstname, $matches);
        if (count($matches) === 0){
            $this->errors[] = 'Имя должно быть символьным!';
        }
        preg_match('/(?=.*[a-zа-яё])/i', $this->lastname, $matches);
        if (count($matches) === 0){
            $this->errors[] = 'Фамилия должна быть символьным!';
        }
        preg_match('/^(0?[1-9]|[12][0-9]|3[01])[\/\-\.](0?[1-9]|1[012])[\/\-\.]\d{4}$/i', $this->birthday, $matches);
        if (count($matches) === 0){
            $this->errors[] = 'Неверная дата!';
        }
        preg_match('/^[0|1]{1}$/i', $this->gender, $matches);
        if (count($matches) === 0){
            $this->errors[] = 'Пол может быть 0 или 1';
        }
        preg_match('/(?=.*[a-zа-яё])+/i', $this->city, $matches);
        if (count($matches) === 0){
            $this->errors[] = 'Город должен быть символьным!';
        }
    }

    public function save()
    {
        $userArray = array(
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'city' => $this->city,
        );
        $this->db->insert( 'users.json',
            $userArray
       );
    }

    public function remove()
    {
        $this->db->delete()
            ->from( 'users.json' )
            ->where( [ 'id' => $this->id ] )
            ->trigger();
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

    public function formatFields()
    {
        $formatObj = new \StdClass();
        foreach (get_object_vars($this) as $key => $value) {
            $formatObj->$key = $value;
        }
        $formatObj->age = self::birthdayToAge($this->birthday);
        $formatObj->genderText = self::genderToText($this->gender);
        return $formatObj;
    }

    public function __toString()
    {
        if ($this->errors) {
            return 'Ошибка создания объекта! ' . '<br>' . implode('<br>', $this->errors) . '<br>';
        }

        $userArray = array(
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'city' => $this->city,
        );
        return json_encode($userArray);
    }
}
