<?php

namespace SkeletonAuthAdmin;

use App\SkeletonAuthAdmin\Models\Admin;
use SkeletonCore\App;

trait AuthTrait
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
     * Check admin credential
     *
     * @param  string $email
     * @param  string $password
     * @return Admin|false
     */
    public static function validateCredential($email, $password)
    {
        $admin = Admin::findByEmail($email);

        try {
            if (is_null($admin)) throw new \Exception("{$email} is not exist.", 1);
            if (!password_verify($password, $admin->password)) throw new \Exception("{$email} is valid but password is incorrect.", 1);

            return $admin;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve logged in admin
     *
     * @return Admin|null
     */
    public static function admin()
    {
        if (!is_null(\Session::get('admin_auth_id')))
        {
            return Admin::findById(\Session::get('admin_auth_id'));
        }

        return null;
    }

    /**
     * Log in the admin
     *
     * @param  id $admin_id
     * @return void
     */
    public static function logInByAdminId($admin_id)
    {
        $login_token = uniqid();

        $admin = Admin::findById($admin_id);

        if (!is_null($admin))
        {
            \Log::info("Login: ". $admin->getFullName());

            \Session::set('admin_auth_id', $admin_id);
            \Session::set('admin_login_token', $login_token);

            $admin->setLoginToken($login_token);
        }
        else
        {
            \Log::error("Admin id {$admin_id} is not exist.");
        }
    }

    /**
     * Log out the admin
     *
     * @return void
     */
    public static function logOut()
    {
        $admin_id = \Session::get('admin_auth_id');
        $admin = Admin::findById($admin_id);

        if (!is_null($admin))
        {
            \Log::info("Logout: ". $admin->getFullName());
            \Session::destroy(['admin_auth_id', 'admin_login_token']);
        }
        else
        {
            \Log::error("Admin id {$admin_id} is not exist.");
        }
    }

    /**
     * Check if admin still log in
     *
     * @return boolean
     */
    public static function check()
    {
        $admin_id = \Session::get('admin_auth_id');
        $admin = Admin::findById($admin_id);

        if (!is_null($admin))
        {
            $is_token_valid = $admin->login_token === \Session::get('admin_login_token');

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
        $this->app->group('/' . config('auth-admin.url_prefix'), function() {
            if (config('auth-admin.modules.register.enabled'))
            {
                $this->group('/register', function() {
                    $this->get('', ["SkeletonAuthAdmin\\RegisterController", "getRegister"])->setName('auth-admin.register');
                    $this->post('', ["SkeletonAuthAdmin\\RegisterController", "postRegister"]);
                    $this->get('/verify/{token}', ["SkeletonAuthAdmin\\RegisterController", "verify"]);
                })->add("SkeletonAuthAdmin\\GuestMiddleware");
            }

            if (config('auth-admin.modules.login.enabled'))
            {
                $this->group('/login', function() {
                    $this->get('', ["SkeletonAuthAdmin\\LoginController", "getLogin"])->setName('auth-admin.login');
                    $this->post('', ["SkeletonAuthAdmin\\LoginController", "postLogin"]);
                })->add("SkeletonAuthAdmin\\GuestMiddleware");

                $this->post('/logout', ["SkeletonAuthAdmin\\LoginController", "logout"])
                    ->setName('auth-admin.logout')
                    ->add("SkeletonAuthAdmin\\AdminMiddleware");
            }

            if (config('auth-admin.modules.forgot_password.enabled'))
            {
                $this->group('/forgot-password', function() {
                    $this->get('', ["SkeletonAuthAdmin\\ForgotPasswordController", "getForgotPassword"])->setName('auth-admin.forgot-password');
                    $this->post('', ["SkeletonAuthAdmin\\ForgotPasswordController", "postForgotPassword"]);
                })->add("SkeletonAuthAdmin\\GuestMiddleware");
            }

            if (config('auth-admin.modules.reset_password.enabled'))
            {
                $this->group('/reset-password', function() {
                    $this->get('/{token}', ["SkeletonAuthAdmin\\ResetPasswordController", "getResetPassword"])->setName('auth-admin.reset-password');
                    $this->post('/{token}', ["SkeletonAuthAdmin\\ResetPasswordController", "postResetPassword"]);
                })->add("SkeletonAuthAdmin\\GuestMiddleware");
            }

            if (config('auth-admin.modules.change_password.enabled'))
            {
                $this->group('/change-password', function() {
                    $this->get('', ["SkeletonAuthAdmin\\ChangePasswordController", "getChangePassword"])->setName('auth-admin.change-password');
                    $this->post('', ["SkeletonAuthAdmin\\ChangePasswordController", "postChangePassword"]);
                })->add("SkeletonAuthAdmin\\AdminMiddleware");
            }

            $this->get('/home', ["SkeletonAuthAdmin\\HomeController", "index"])
            ->setName('auth-admin.home')
            ->add("SkeletonAuthAdmin\\AdminMiddleware");
        });
    }
}
