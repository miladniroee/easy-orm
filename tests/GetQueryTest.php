<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;


class GetQueryTest extends TestCase
{

    public function testQuery1()
    {
        $ModelQuery = Users::select('name')->query();
        $this->assertEquals('SELECT name FROM `users`', $ModelQuery);
    }

    public function testQuery2()
    {
        $ModelQuery = Users::query();
        $this->assertEquals('SELECT * FROM `users`', $ModelQuery);
    }

    public function testQuery3()
    {
        $ModelQuery = Users::select(['name', 'username', 'created_at'])->query();
        $this->assertEquals('SELECT name,username,created_at FROM `users`', $ModelQuery);
    }

    public function testQuery4()
    {
        $Users = new Users();
        $Users->select(['name', 'username', 'created_at']);
        $this->assertEquals('SELECT name,username,created_at FROM `users`', $Users->query());
    }

    public function testQuery5()
    {
        $ModelQuery = Users::update(['name' => 'milad'])->where('id','=',2)->query();
        $this->assertEquals('UPDATE `users` SET name=? WHERE `id` = ?', $ModelQuery);
    }
}
