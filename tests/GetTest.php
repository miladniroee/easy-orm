<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{
    public function testGet1()
    {
        $Get = Users::select('name')->get();
        $this->assertIsArray($Get);
    }

    public function testGet2()
    {
        $Get = Users::where('id', '=', -7)->get();
        $this->assertEmpty($Get);
    }

    public function testGet3()
    {
        $Get = Users::get();
        $this->assertIsArray($Get);
    }

    public function testGet4()
    {
        $Users = new Users();
        $Get = $Users->get();
        $this->assertIsArray($Get);
    }
}
