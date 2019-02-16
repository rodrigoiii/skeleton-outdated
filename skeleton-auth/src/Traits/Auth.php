<?php

namespace SkeletonAuth\Traits;

use SkeletonAuth\User;
use SkeletonCore\App;

trait Auth
{
    /**
     * @var App
     */
    protected $app;

    /**
     * Create Auth instance
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Check user credential
     *
     * @param  string $email
     * @param  string $password
     * @return User|false
     */
    public static function validateCredential($email, $password)
    {
        $user = User::findByEmail($email);

        try {
            if (is_null($user)) throw new \Exception("{$email} is not exist.", 1);
            if (!password_verify($password, $user->password)) throw new \Exception("{$email} is valid but password is incorrect.", 1);

            return $user;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve logged in user
     *
     * @return User|null
     */
    public static function user()
    {
        if (!is_null(\Session::get('user_auth_id')))
        {
            return User::find(\Session::get('user_auth_id'));
        }

        return null;
    }

    /**
     * Log in the user
     *
     * @param  id $user_id
     * @return void
     */
    public static function logInByUserId($user_id)
    {
        $login_token = uniqid();

        $user = User::find($user_id);

        if (!is_null($user))
        {
            \Log::info("Login: ". $user->getFullName());

            \Session::set('user_auth_id', $user_id);
            \Session::set('user_login_token', $login_token);

            $user->setLoginToken($login_token);
        }
        else
        {
            \Log::error("User id {$user_id} is not exist.");
        }
    }

    /**
     * Log out the user
     *
     * @return void
     */
    public static function logOut()
    {
        $user_id = \Session::get('user_auth_id');
        $user = User::find($user_id);

        if (!is_null($user))
        {
            \Log::info("Logout: ". $user->getFullName());
            \Session::destroy(['user_auth_id', 'user_login_token']);
        }
        else
        {
            \Log::error("User id {$user_id} is not exist.");
        }
    }

    /**
     * Check if user still log in
     *
     * @return boolean
     */
    public static function check()
    {
        $user_id = \Session::get('user_auth_id');
        $user = User::find($user_id);

        if (!is_null($user))
        {
            $is_token_valid = $user->login_token === \Session::get('user_login_token');

            if ($is_token_valid)
            {
                return true;
            }

            static::logout();
        }

        return false;
    }

    /**
     * Initialize the Auth routes
     *
     * @return void
     */
    public function routes()
    {
        $this->app->group('/' . config('auth.url_prefix'), function() {
            if (config('auth.modules.register.enabled'))
            {
                $this->group('/register', function() {
                    $this->get('', ["SkeletonAuthApp\\RegisterController", "getRegister"])->setName('auth.register');
                    $this->post('', ["SkeletonAuthApp\\RegisterController", "postRegister"]);
                    $this->get('/verify/{token}', ["SkeletonAuthApp\\RegisterController", "verify"]);
                })->add("SkeletonAuthApp\\GuestMiddleware");
            }

            if (config('auth.modules.login.enabled'))
            {
                $this->group('/login', function() {
                    $this->get('', ["SkeletonAuthApp\\LoginController", "getLogin"])->setName('auth.login');
                    $this->post('', ["SkeletonAuthApp\\LoginController", "postLogin"]);
                })->add("SkeletonAuthApp\\GuestMiddleware");

                $this->post('/logout', ["SkeletonAuthApp\\LoginController", "logout"])
                    ->setName('auth.logout')
                    ->add("SkeletonAuthApp\\UserMiddleware");
            }

            if (config('auth.modules.forgot_password.enabled'))
            {
                $this->group('/forgot-password', function() {
                    $this->get('', ["SkeletonAuthApp\\ForgotPasswordController", "getForgotPassword"])->setName('auth.forgot-password');
                    $this->post('', ["SkeletonAuthApp\\ForgotPasswordController", "postForgotPassword"]);
                })->add("SkeletonAuthApp\\GuestMiddleware");
            }

            if (config('auth.modules.reset_password.enabled'))
            {
                $this->group('/reset-password', function() {
                    $this->get('/{token}', ["SkeletonAuthApp\\ResetPasswordController", "getResetPassword"])->setName('auth.reset-password');
                    $this->post('/{token}', ["SkeletonAuthApp\\ResetPasswordController", "postResetPassword"]);
                })->add("SkeletonAuthApp\\GuestMiddleware");
            }

            if (config('auth.modules.account_setting.enabled'))
            {
                $this->group('/account-setting', function() {
                    $this->get('', ["SkeletonAuthApp\\AccountSettingController", "getAccountSetting"])->setName('auth.account-setting');
                    $this->post('', ["SkeletonAuthApp\\AccountSettingController", "postAccountSetting"]);
                })->add("SkeletonAuthApp\\UserMiddleware");
            }

            $this->get('/home', ["SkeletonAuthApp\\HomeController", "index"])
            ->setName('auth.home')
            ->add("SkeletonAuthApp\\UserMiddleware");
        });
    }

    public function apiRoutes()
    {
        // jquery validation
        $this->app->group('/jv', function() {
            $this->get('/email-exist', ["SkeletonAuthApp\\JqueryValidationController", "emailExist"]);
        })->add("XhrMiddleware");
    }
}
