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

    public function testUserValid(){
        $result = $this->user->isValid();
        $this->assertTrue($result);
    }

    public function testUserFirstNameEmpty(){
        $this->user->setFirstName("");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserFirstNameWithNumbers(){
        $this->user->setFirstName("amine14");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserFirstNameSpecialCharacters(){
        $this->user->setFirstName("@min###");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserLastNameEmpty(){
        $this->user->setLastName("");
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

    public function testEmailEmpty() {
        $this->user->setEmail("");
        $this->assertFalse($this->user->isValid());
    }

    public function testInvalidEmail() {
        $this->user->setEmail("amine.gmail.com");
        $this->assertFalse($this->user->isValid());
    }

    public function testDateOfBirthEmpty(){
        $this->user->setDateOfBirth("");
        $this->assertFalse($this->user->isValid());
    }

    public function testInvalidDateFormat(){
        $this->user->setDateOfBirth("20/02-2009");
        $this->assertFalse($this->user->isValid());
    }

    public function testUserUnder13(){
        $this->user->setDateOfBirth("20/02/2009");
        $this->assertFalse($this->user->isValid());
    }

    public function testpasswordEmpty(){
        $this->user->setPassword("");
        $this->assertFalse($this->user->isValid());
    }

    public function testpasswordTooShort(){
        $this->user->setPassword("123");
        $this->assertFalse($this->user->isValid());
    }

    public function testpasswordTooLong(){
        $this->user->setPassword("1231111111111111111111111111111111111111111");
        $this->assertFalse($this->user->isValid());
    }

}
