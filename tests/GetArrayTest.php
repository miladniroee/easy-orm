<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class GetArrayTest extends TestCase
{
    public function testArray1()
    {
        $ModelArray = Users::select('name')->toArray();
        $this->assertArrayHasKey('name', $ModelArray[0]);
    }

    public function testArray2()
    {
        $ModelJson = Users::toArray();
        $this->assertIsArray($ModelJson);
    }

    public function testArray3()
    {
        $ModelArray = Users::select(['name', 'username'])->toArray();
        $this->assertArrayHasKey('name',$ModelArray[0]);
        $this->assertArrayHasKey('username',$ModelArray[0]);
    }

    public function testArray4()
    {
        $Users = new Users();
        $Users->select(['name', 'username']);
        $Array = $Users->toArray();
        $this->assertArrayHasKey('name',$Array[0]);
        $this->assertArrayHasKey('username',$Array[0]);
    }


}
