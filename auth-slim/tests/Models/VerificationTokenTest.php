<?php

use AuthSlim\Models\VerificationToken;
use AuthSlim\Utilities\EncryptDecrypt;
use PHPUnit\Framework\TestCase;

class VerificationTokenTest extends TestCase
{
    /**
     * @test
     */
    public function method_token_exist_should_return_true_if_token_is_really_exist()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_REGISTER,
            'token' => $token,
            'data' => $data
        ]);

        $this->assertTrue(VerificationToken::tokenExist($verification_token->token));
    }

    /**
     * @test
     */
    public function method_token_exist_should_return_false_if_token_is_not_exist()
    {
        do {
            $token = uniqid();
        } while ($token == VerificationToken::findByToken($token));

        $this->assertFalse(VerificationToken::tokenExist($token));
    }

    /**
     * @test
     */
    public function method_is_token_for_register_expired_should_return_false_if_token_is_not_yet_expired()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_REGISTER,
            'token' => $token,
            'data' => $data
        ]);

        VerificationToken::$REGISTER_REQUEST_EXPIRATION = 10; // 10 seconds before expiration

        $this->assertFalse($verification_token->isTokenForRegisterExpired());
    }

    /**
     * @test
     */
    public function method_is_token_for_register_expired_should_return_true_if_token_is_already_expired()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_REGISTER,
            'token' => $token,
            'data' => $data
        ]);

        VerificationToken::$REGISTER_REQUEST_EXPIRATION = 0;

        $this->assertTrue($verification_token->isTokenForRegisterExpired());
    }

    /**
     * @test
     */
    public function method_is_token_for_reset_password_expired_should_return_false_if_token_is_not_yet_expired()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_RESET_PASSWORD,
            'token' => $token,
            'data' => $data
        ]);

        VerificationToken::$RESET_PASSWORD_REQUEST_EXPIRATION = 10; // 10 seconds before expiration

        $this->assertFalse($verification_token->isTokenForResetPasswordExpired());
    }

    /**
     * @test
     */
    public function method_is_token_for_reset_password_expired_should_return_true_if_token_is_already_expired()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_RESET_PASSWORD,
            'token' => $token,
            'data' => $data
        ]);

        VerificationToken::$RESET_PASSWORD_REQUEST_EXPIRATION = 0;

        $this->assertTrue($verification_token->isTokenForResetPasswordExpired());
    }

    /**
     * @test
     */
    public function method_is_verified_should_return_false_if_token_is_not_yet_verified()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_REGISTER,
            'token' => $token,
            'data' => $data,
            'is_verified' => 0
        ]);

        $this->assertEquals(0, $verification_token->isVerified());
    }

    /**
     * @test
     */
    public function method_is_verified_should_return_true_if_token_is_already_verified()
    {
        $token = uniqid();
        $data = EncryptDecrypt::encrypt("dummytext", "dummytext-key");

        $verification_token = VerificationToken::create([
            'type' => VerificationToken::TYPE_REGISTER,
            'token' => $token,
            'data' => $data,
            'is_verified' => 1
        ]);

        $this->assertEquals(1, $verification_token->isVerified());
    }
}