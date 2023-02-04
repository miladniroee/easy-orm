<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    public function testSelect1()
    {
        $Select = Users::select('id')->first();
        $this->assertTrue(property_exists($Select, 'id'));
    }

    public function testSelect2()
    {
        $Select = Users::select(['name', 'username'])->first();
        $this->assertTrue(property_exists($Select, 'name'));
        $this->assertTrue(property_exists($Select, 'username'));
    }
}
