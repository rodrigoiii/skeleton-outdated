<?php

use Framework\Validation\Rules\PasswordVerify;
use PHPUnit\Framework\TestCase;

class PasswordVerifyTest extends TestCase
{
    /**
     * @test
     */
    public function should_be_valid()
    {
        $this->assertTrue((new PasswordVerify(password_hash(sha1("same password"), PASSWORD_DEFAULT)))->validate("same password"));
    }

    /**
     * @test
     */
    public function should_be_invalid()
    {
        $this->assertFalse((new PasswordVerify(password_hash(sha1("not! same password"), PASSWORD_DEFAULT)))->validate("not! same"));
    }
}