<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    /**
     * WARNING: this test would DELETE all entries from users table
     */
    public function testDelete1()
    {
        $Delete = Users::delete()->exec();
        $this->assertIsInt($Delete);
    }

    /**
     * WARNING: this test would DELETE id=1 from users table
     */
    public function testDelete2()
    {
        $Delete = Users::delete()->where('id', '=', 1)->exec();
        $this->assertIsInt($Delete);
    }


    public function testDelete3()
    {
        $Delete = Users::delete()->where('id', '=', -1)->exec();
        $this->assertEquals(0, $Delete);
    }
}
