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
            $this->save();
        } else {
            $this->id = $selectResult[0]['id'];
            $this->firstname = $selectResult[0]['firstname'];
            $this->lastname = $selectResult[0]['lastname'];
            $this->birthday = $selectResult[0]['birthday'];
            $this->gender = $selectResult[0]['gender'];
            $this->city = $selectResult[0]['city'];
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
