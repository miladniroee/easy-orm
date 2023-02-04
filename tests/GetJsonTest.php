<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class GetJsonTest extends TestCase
{
    public function testJson1()
    {
        $ModelJson = Users::select('name')->json();
        $this->assertJson($ModelJson);
    }

    public function testJson2()
    {
        $ModelJson = Users::json();
        $this->assertJson( $ModelJson);
    }

    public function testJson3()
    {
        $ModelJson = Users::select(['name', 'username'])->json();
        $this->assertJson($ModelJson);
    }

    public function testJson4()
    {
        $Users = new Users();
        $Users->select(['name', 'username']);
        $this->assertJson($Users->json());
    }

}
