<?php

use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_print_hello_world()
    {
        $this->expectOutputString("hello world");
        echo "hello world";
    }
}