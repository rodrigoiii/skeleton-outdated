<?php

namespace SkeletonAuth\Auth;

use App\SkeletonAuth\Models\User;
use SkeletonCore\App;

trait AuthTrait
{
    use HandlerTrait;

    /**
     * @var App
     */
    protected $app;

    /**
     * Create Auth instance
     *
     * @param App $app [description]
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
        if (!is_null(\Session::get('auth_user_id')))
        {
            return User::find(\Session::get('auth_user_id'));
        }

        return null;
    }

    /**
     * Log in the user
     *
     * @param  id $user_id
     * @return void
     */
    public static function loggedInByUserId($user_id)
    {
        $logged_in_token = uniqid();

        $user = User::find($user_id);

        if (!is_null($user))
        {
            \Log::info("Login: ". $user->getFullName());

            \Session::set('auth_user_id', $user_id);
            \Session::set('logged_in_token', $logged_in_token);
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
    public static function loggedOut()
    {
        $user_id = \Session::get('auth_user_id');
        $user = User::find($user_id);

        if (!is_null($user))
        {
            \Log::info("Logout: ". $user->getFullName());
            \Session::destroy(['auth_user_id', 'logged_in_token']);
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
        $user_id = \Session::get('auth_user_id');
        $user = User::find($user_id);

        return !is_null($user);
    }

    /**
     * Initialize the Auth routes
     *
     * @return void
     */
    public function routes()
    {
        $this->app->group('/auth', function() {
            if (config('auth.register.enabled'))
            {
                $this->group('/register', function() {
                    $this->get('', ["SkeletonAuth\\RegisterController", "getRegister"])->setName('auth.register');
                    $this->post('', ["SkeletonAuth\\RegisterController", "postRegister"]);
                    $this->get('/verify/{token}', ["SkeletonAuth\\RegisterController", "verify"]);
                })->add("SkeletonAuth\\GuestMiddleware");
            }

            if (config('auth.login.enabled'))
            {
                $this->group('/login', function() {
                    $this->get('', ["SkeletonAuth\\LoginController", "getLogin"])->setName('auth.login');
                    $this->post('', ["SkeletonAuth\\LoginController", "postLogin"]);
                })->add("SkeletonAuth\\GuestMiddleware");

                $this->post('/logout', ["SkeletonAuth\\LoginController", "logout"])
                    ->setName('auth.logout')
                    ->add("SkeletonAuth\\UserMiddleware");
            }

            if (config('auth.forgot_password.enabled'))
            {
                $this->group('/forgot-password', function() {
                    $this->get('', ["SkeletonAuth\\ForgotPasswordController", "getForgotPassword"])->setName('auth.forgot-password');
                    $this->post('', ["SkeletonAuth\\ForgotPasswordController", "postForgotPassword"]);
                })->add("SkeletonAuth\\GuestMiddleware");
            }

            if (config('auth.reset_password.enabled'))
            {
                $this->group('/reset-password', function() {
                    $this->get('/{token}', ["SkeletonAuth\\ResetPasswordController", "getResetPassword"])->setName('auth.reset-password');
                    $this->post('/{token}', ["SkeletonAuth\\ResetPasswordController", "postResetPassword"]);
                })->add("SkeletonAuth\\GuestMiddleware");
            }

            if (config('auth.change_password.enabled'))
            {
                $this->group('/change-password', function() {
                    $this->get('', ["SkeletonAuth\\ChangePasswordController", "getChangePassword"])->setName('auth.change-password');
                    $this->post('', ["SkeletonAuth\\ChangePasswordController", "postChangePassword"]);
                })->add("SkeletonAuth\\UserMiddleware");
            }

            $this->get('/home', ["SkeletonAuth\\HomeController", "index"])
            ->setName('auth.home')
            ->add("SkeletonAuth\\UserMiddleware");
        });
    }
}
