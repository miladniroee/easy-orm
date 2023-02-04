<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\OtherTable;
use PHPUnit\Framework\TestCase;

class OtherTableTest extends TestCase
{
    public function testOtherTable()
    {
        $OtherTable = OtherTable::query();
        $this->assertEquals('SELECT * FROM `users`',$OtherTable);
    }
}
