<?php

use Framework\Validation\Rules\PasswordStrength;
use PHPUnit\Framework\TestCase;

class PasswordStrengthTest extends TestCase
{
    private static $validator;

    public static function setUpBeforeClass()
    {
        $min_length = 3;
        $lower = 3;
        $upper = 3;
        $number = 3;
        $special_char = 3;

        static::$validator = new PasswordStrength($min_length, $lower, $upper, $number, $special_char);
    }

    public function valid_password_provider()
    {
        $provider = json_decode(file_get_contents(__DIR__ . "/_files/password-strength-data-provider.json"), true);

        return  array_map(function ($password) {
                    return [$password];
                }, $provider['valid']);
    }

    public function invalid_password_provider()
    {
        $provider = json_decode(file_get_contents(__DIR__ . "/_files/password-strength-data-provider.json"), true);

        return  array_map(function ($password) {
                    return [$password];
                }, $provider['invalid']);
    }

    /**
     * @test
     * @dataProvider valid_password_provider
     */
    public function should_validate_valid_password($valid_password_data)
    {
        $this->assertTrue(static::$validator->validate($valid_password_data), "{$valid_password_data} is not consider as valid");
    }

    /**
     * @test
     * @dataProvider invalid_password_provider
     */
    public function should_validate_invalid_password($invalid_password_data)
    {
        $this->assertFalse(static::$validator->validate($invalid_password_data), "{$invalid_password_data} is not consider as invalid");
    }
}