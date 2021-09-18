<?php
class Person
{
    public $name;
    public $date_of_birth;
    public $email;
    public $ssn;

    public function __construct($name, $date_of_birth, $email, $ssn)
    {
        $this->name = $name;
        $this->date_of_birth = $date_of_birth;
        $this->email = $email;
        $this->ssn = $ssn;
    }
    public function to_string()
    {
        return ($this->name . "," . $this->date_of_birth . "," . $this->email . "," . $this->ssn);
    }
}
