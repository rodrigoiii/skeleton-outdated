<?php

namespace SkeletonAuth\AuthTrait;

use App\Middlewares\GuestMiddleware;
use App\Middlewares\UserMiddleware;
use App\Models\User;
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
            \Log::info("Login: ". $user->first_name . " " . $user->last_name);

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
            \Log::info("Logout: ". $user->first_name . " " . $user->last_name);
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
            $this->group('/login', function() {
                $this->get('', ["LoginController", "getLogin"])->setName('auth.login');
                $this->post('', ["LoginController", "postLogin"]);
            })->add(new GuestMiddleware);

            $this->post('/logout', ["LoginController", "logout"])
                ->setName('auth.logout')
                ->add(new UserMiddleware);

            $this->group('/register', function() {
                $this->get('', ["RegisterController", "getRegister"])->setName('auth.register');
                $this->post('', ["RegisterController", "postRegister"]);
                $this->get('/verify/{token}', ["RegisterController", "verify"]);
            })->add(new GuestMiddleware);

            $this->group('/forgot-password', function() {
                $this->get('', ["ForgotPasswordController", "getForgotPassword"])->setName('auth.forgot-password');
                $this->post('', ["ForgotPasswordController", "postForgotPassword"]);
            })->add(new GuestMiddleware);

            $this->group('/reset-password', function() {
                $this->get('/{token}', ["ResetPasswordController", "getResetPassword"])->setName('auth.reset-password');
                $this->post('/{token}', ["ResetPasswordController", "postResetPassword"]);
            })->add(new GuestMiddleware);

            $this->group('/change-password', function() {
                $this->get('', ["ChangePasswordController", "getChangePassword"])->setName('auth.change-password');
                $this->post('', ["ChangePasswordController", "postChangePassword"]);
            })->add(new UserMiddleware);

            $this->get('/home', function() {
                return "home";
            })
            ->setName('auth.home')
            ->add(new UserMiddleware);
        });
    }
}
