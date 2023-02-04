<?php

namespace Niroee\EasyOrmTests;

use Niroee\EasyOrmTests\Data\Users;
use PHPUnit\Framework\TestCase;

class GetFirstTest extends TestCase
{
    public function testFirst1()
    {
        $First = Users::first();
        $this->assertIsNotArray($First);
    }

    public function testFirst2()
    {
        $First = Users::first();
        $this->assertIsObject($First);
    }

    public function testFirst3()
    {
        // when there is no data to return
        $First = Users::where('id', '=', -12)->first();
        $this->assertIsBool($First);
    }
}
