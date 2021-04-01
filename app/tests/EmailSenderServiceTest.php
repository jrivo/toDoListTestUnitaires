<?php

use \PHPUnit\Framework\TestCase;

class EmailSenderServiceTest extends TestCase
{

    /** @var EmailSenderService $ess */
    private $ess;

    protected function setUp(): void
    {
        $this->ess = $this->getMockBuilder(EmailSenderService::class)
            ->onlyMethods(['sendMail'])
            ->getMock();

        parent::setUp();
    }

    public function eigthItemsAdded() //Waiting ToDoList class implemented to add it inside
    {
        $this->ess->expects($this->once())
            ->method('sendMail')
            ->willReturn("Email sent");
    }
}