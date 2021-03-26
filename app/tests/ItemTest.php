<?php

use PHPUnit\Framework\TestCase;

require '../src/Utils/ItemList.php';

class ItemTest extends TestCase
{
    private ItemList $item;

    protected function setUp(): void
    {
        $this->item = new ItemList('new', 'this is a test', date_create()->format('Y-m-d H:i:s'));
        parent::setUp();
    }

    public function isValidNominal()
    {
        $this->assertTrue($this->item->isValid());
    }

}