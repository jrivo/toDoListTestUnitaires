<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Utils\UserAccount;

class UserAccountTest extends TestCase{
    private UserAccount $user;


    protected function setUp(): void
    {
        $this->user = new UserAccount("amine", "ziani","test@email.fr","mypassword123", "23/07/1995");
        parent::setUp();
    }


    // testing a username with valid properties
    public function testUserValid(){
        $result = $this->user->isValid();
        $this->assertTrue($result);
    }

    // first name can't be smpty
    public function testUserFirstNameEmpty(){
        $this->user->setFirstName("");
        $this->assertFalse($this->user->isValid());
    }

    // first name can't have more than a 100 characters
    public function testUserFirstNameTooLong() {
        $this->user->setFirstName("djnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndj");
        $this->assertFalse($this->user->isValid());
    }


    // names can't have numbers in them
    public function testUserFirstNameWithNumbers(){
        $this->user->setFirstName("amine14");
        $this->assertFalse($this->user->isValid());
    }

    // names can't have special characters
    public function testUserFirstNameSpecialCharacters(){
        $this->user->setFirstName("@min###");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserLastNameEmpty(){
        $this->user->setLastName("");
        $this->assertFalse($this->user->isValid());
    }

    //last name can have a 100 characters max
    public function testUserLastNameTooLong() {
        $this->user->setLastName("djnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndjdjnfhskjgnvjfifjfndj");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserLastNameWithNumbers(){
        $this->user->setLastName("z1an777");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserLastNameSpecialCharacters(){
        $this->user->setLastName("z$$^");
        $this->assertFalse($this->user->isValid());
    }

    // email is required
    public function testEmailEmpty() {
        $this->user->setEmail("");
        $this->assertFalse($this->user->isValid());
    }

    // email format has to be valid
    public function testInvalidEmail() {
        $this->user->setEmail("amine.gmail.com");
        $this->assertFalse($this->user->isValid());
    }

    // birthdate is required
    public function testDateOfBirthEmpty(){
        $this->user->setDateOfBirth("");
        $this->assertFalse($this->user->isValid());
    }

    // format of date of birth has to be valid
    public function testInvalidDateFormat(){
        $this->user->setDateOfBirth("20/02-2009");
        $this->assertFalse($this->user->isValid());
    }

    // testing a user who's no old enough (under 13 )
    public function testUserUnder13(){
        $this->user->setDateOfBirth("20/02/2009");
        $this->assertFalse($this->user->isValid());
    }

    // password is required
    public function testpasswordEmpty(){
        $this->user->setPassword("");
        $this->assertFalse($this->user->isValid());
    }

    // the minimum length for passwords is 8 characters
    public function testpasswordTooShort(){
        $this->user->setPassword("123");
        $this->assertFalse($this->user->isValid());
    }

    // the maximum length for passwords is 40
    public function testpasswordTooLong(){
        $this->user->setPassword("1231111111111111111111111111111111111111111");
        $this->assertFalse($this->user->isValid());
    }

}
