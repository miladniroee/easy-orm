<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    /**
     * WARNING: this test would insert into users table
     */
    public function testInsert1()
    {
        $Insert = Users::insert(
            [
                'name' => 'milad',
                'username' => 'milad',
            ]
        )->exec();
        $this->assertIsInt($Insert);
    }
}
