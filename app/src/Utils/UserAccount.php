<?php

namespace App\Utils;


use App\Entity\User;

class UserAccount {
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $dateOfBirth;
    public function __construct($firstName, $lastName, $email,$password , $dateOfBirth)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->dateOfBirth = $dateOfBirth;
    }

    public function isValid(): bool
    {
        $tz  = new \DateTimeZone('Europe/Paris');
        $dateOfBirth = \DateTime::createFromFormat('d/m/Y', $this->dateOfBirth, $tz); // formatting the date of birth
        $age = 0;
        $firstNameIsValid = false;
        $lastNameIsValid = false;
        $ageIsValid = false;
        $passwordIsValid = false;


        // name validation
        //checking if the first and last name are set and if the string format is valid
        $firstNameIsValid = !empty($this->firstName)
            && $firstNameIsValid = preg_match("/^[\s,.'-]*\p{L}[\p{L}\s,.'-]*$/u", $this->firstName);
        $lastNameIsValid = !empty($this->lastName)
            && $lastNameIsValid = preg_match("/^[\s,.'-]*\p{L}[\p{L}\s,.'-]*$/u", $this->lastName);


        // age validation
        if($dateOfBirth){
            $age = $dateOfBirth->diff(new \DateTime('now', $tz))->y; // getting the age of the user
            if($age >= 13) // checking if the user is at least 13 years old
                $ageIsValid = true;
        }

        // password validation
        if(!empty($this->password) && strlen($this->password) >= 8 && strlen($this->password) <= 40)
            $passwordIsValid = true;

        // returns false if one or more fields aren't valid
        return
            $firstNameIsValid
            && $lastNameIsValid
            && !empty($this->email)
            && filter_var($this->email, FILTER_VALIDATE_EMAIL)
            && $passwordIsValid
            && $ageIsValid;
    }

    public function save($entityManager){
        if($this->isValid()) { // checking if the info is valid before saving the data in the database
            $user = new User(); // creating a new instance of user entity
            $tz  = new \DateTimeZone('Europe/Paris');
            $user->setFirstName($this->firstName); // adding data to entity
            $user->setLastName($this->lastName);
            if($entityManager->getRepository(User::class)->findOneBy(['email' => $this->email])) //checking if the email already exists
                return false;
            $user->setEmail($this->email);
            $user->setPassword($this->password);
            $user->setBirthday(\DateTime::createFromFormat('d/m/Y', $this->dateOfBirth, $tz));
            $entityManager->persist($user);
            $entityManager->flush(); // saving data in the database
            return true; //returns true when the data is saved successfully
        }
        else
            return false; // doesn't save the data and returns false if the data isn't valid
    }

    public function displayData($entityManager) {

    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }



}

