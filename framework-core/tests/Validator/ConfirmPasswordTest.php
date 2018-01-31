<?php

use Framework\Validation\Rules\ConfirmPassword;
use PHPUnit\Framework\TestCase;

class ConfirmPasswordTest extends TestCase
{
    /**
     * @test
     */
    public function should_be_valid()
    {
        $this->assertTrue((new ConfirmPassword("same password"))->validate("same password"));
    }

    /**
     * @test
     */
    public function should_be_invalid()
    {
        $this->assertFalse((new ConfirmPassword("not! same password"))->validate("not! same"));
    }
}