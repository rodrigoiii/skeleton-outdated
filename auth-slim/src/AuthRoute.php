<?php

namespace AuthSlim;

use AuthSlim\Auth\Auth;
use AuthSlim\Models\VerificationToken;
use Respect\Validation\Validator as v;

class AuthRoute
{
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = [
            'url_prefix' => isset($options['url_prefix']) ? $options['url_prefix'] : "auth",
            'url_login' => isset($options['url_login']) ? $options['url_login'] : "login",
            'url_logout' => isset($options['url_logout']) ? $options['url_logout'] : "logout",

            'login_session_expiration' => isset($options['login_session_expiration']) ? $options['login_session_expiration'] : Auth::$LOGIN_SESSION_EXPIRATION, // 30 minutes,
            'login_attempt_length' => isset($options['login_attempt_length']) ? $options['login_attempt_length'] : Auth::$LOGIN_ATTEMPT_LENGTH, // 5 attempts
            'login_lock_time' => isset($options['login_lock_time']) ? $options['login_lock_time'] : Auth::$LOGIN_LOCK_TIME, // 30 minutes

            'register_request_expiration' => isset($options['register_request_expiration']) ? $options['register_request_expiration'] : VerificationToken::$REGISTER_REQUEST_EXPIRATION, // 5 hours

            'ValidToLoginMiddleware' => isset($options['ValidToLoginMiddleware']) ? $options['ValidToLoginMiddleware'] : "ValidToLogin",
            'UserMiddleware' => isset($options['UserMiddleware']) ? $options['UserMiddleware'] : "User",
            'GuestMiddleware' => isset($options['GuestMiddleware']) ? $options['GuestMiddleware'] : "Guest",

            'AuthController' => isset($options['AuthController']) ? $options['AuthController'] : "AuthController",
            'RegisterController' => isset($options['RegisterController']) ? $options['RegisterController'] : "RegisterController",
            'ForgotPasswordController' => isset($options['ForgotPasswordController']) ? $options['ForgotPasswordController'] : "ForgotPasswordController",
            'ResetPasswordController' => isset($options['ResetPasswordController']) ? $options['ResetPasswordController'] : "ResetPasswordController",
            'AccountDetailController' => isset($options['AccountDetailController']) ? $options['AccountDetailController'] : "AccountDetailController"
        ];

        Auth::$LOGIN_SESSION_EXPIRATION = $this->options['login_session_expiration'];
        Auth::$LOGIN_ATTEMPT_LENGTH = $this->options['login_attempt_length'];
        Auth::$LOGIN_LOCK_TIME = $this->options['login_lock_time'];

        VerificationToken::$REGISTER_REQUEST_EXPIRATION = $this->options['register_request_expiration'];

        v::with("AuthSlim\\Validation\\Rules\\");
    }

    public function routes($app, $container)
    {
        $options = $this->options;

        $app->group("/" . $options['url_prefix'], function() use ($container, $options)
        {
            # login
            $this->group("[/" . $options['url_login'] . "]", function () use ($container, $options)
            {
                # get login
                $this->get('', $options['AuthController'] . ":getLogin")->setName("auth.login");

                // # post login
                $this->post('', $options['AuthController'] . ":postLogin");
            })
            ->add(new $options['ValidToLoginMiddleware']($container))
            ->add(new $options['GuestMiddleware']($container));

            # registration
            $this->group("/register", function () use ($options) {
                $this->get('', $options['RegisterController'] . ":getRegister")->setName('auth.register');
                $this->post('', $options['RegisterController'] . ":postRegister");
            })
            ->add(new $options['GuestMiddleware']($container));

            # confirm registration
            $this->get('/verify/register-user/{token}', $options['RegisterController'] . ":verifyUser")
            ->add(new $options['GuestMiddleware']($container))
            ->setName('auth.verify.register-user');

            # update account detail
            $this->group("/account-detail", function () use ($options) {
                $this->get('', $options['AccountDetailController'] . ":index")->setName('auth.account-detail');

                $this->get('/edit', $options['AccountDetailController'] . ":edit")->setName('auth.account-detail.update');
                $this->post('/edit', $options['AccountDetailController'] . ":update");
            })
            ->add(new $options['UserMiddleware']($container));

            # forgot password
            $this->group("/forgot-password", function () use ($options) {
                $this->get('', $options['ForgotPasswordController'] . ":getForgotPassword")->setName('auth.forgot-password');
                $this->post('', $options['ForgotPasswordController'] . ":postForgotPassword");
            })
            ->add(new $options['GuestMiddleware']($container));

            # reset password
            $this->group("/reset-password", function () use ($options) {
                $this->get('/{token}', $options['ResetPasswordController'] . ":getResetPassword")->setName('auth.reset-password');
                $this->post('/{token}', $options['ResetPasswordController'] . ":postResetPassword");
            })
            ->add(new $options['GuestMiddleware']($container));

            # logout
            $this->post('/' . $options['url_logout'], $options['AuthController'] . ":logout")
            ->add(new $options['UserMiddleware']($container))
            ->setName("auth.logout");
        });
    }
}