<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    /**
     * WARNING: this test would update all entries from users table
     */
    public function testUpdate1()
    {
        $Update = Users::update(['name' => 'milad'])->exec();
        $this->assertIsInt($Update);
    }

    /**
     * WARNING: this test would update id=1 from users table
     */
    public function testUpdate2()
    {
        $Update = Users::update(['name' => 'easy-orm'])->where('id', '=', 1)->exec();
        $this->assertIsInt($Update);
    }


    public function testUpdate3()
    {
        $Update = Users::update(['name' => 'easy-orm'])->where('id', '=', -1)->exec();
        $this->assertEquals(0, $Update);
    }
}
