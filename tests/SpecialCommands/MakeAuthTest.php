<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class MakeAuthTest extends TestCase
{
    protected static $tester;
    protected static $namespace;
    protected static $commands_path;

    public static function setUpBeforeClass()
    {
        $app = new Application;

        self::$tester = new ApplicationTester($app);
        static::$namespace = config("app.namespace");
        static::$commands_path = system_path("classes/Console/Commands");
    }

    /**
     * @test
     */
    public function make_model_template_method()
    {
        $user_template = strtr(file_get_contents(static::$commands_path . "/templates/_auth/models/user.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        $file = fopen(app_path("Models/UserTemp.php"), "w");
        fwrite($file, $user_template);
        fclose($file);

        $this->assertFileExists(app_path("Models/UserTemp.php"));
        $this->assertEquals(file_get_contents(app_path("Models/UserTemp.php")), $user_template);
    }

    /**
     * @test
     */
    public function make_view_template_method()
    {
        $account_detail_index = file_get_contents(static::$commands_path . "/templates/_auth/views/account-detail/index.twig.dist");
        $account_detail_edit = file_get_contents(static::$commands_path . "/templates/_auth/views/account-detail/edit.twig.dist");

        $layouts_partials_nav = file_get_contents(static::$commands_path . "/templates/_auth/views/layouts/partials/nav.twig.dist");
        $layouts_app = file_get_contents(static::$commands_path . "/templates/_auth/views/layouts/app.twig.dist");

        $templates_error_list_bootstrap = file_get_contents(static::$commands_path . "/templates/_auth/views/templates/error-list/bootstrap.twig.dist");
        $templates_flash_messages_bootstrap = file_get_contents(static::$commands_path . "/templates/_auth/views/templates/flash-messages/bootstrap.twig.dist");

        $authenticated_home_page = file_get_contents(static::$commands_path . "/templates/_auth/views/authenticated-home-page.twig.dist");
        $change_password = file_get_contents(static::$commands_path . "/templates/_auth/views/change-password.twig.dist");
        $forgot_password = file_get_contents(static::$commands_path . "/templates/_auth/views/forgot-password.twig.dist");
        $login = file_get_contents(static::$commands_path . "/templates/_auth/views/login.twig.dist");
        $register = file_get_contents(static::$commands_path . "/templates/_auth/views/register.twig.dist");
        $reset_password = file_get_contents(static::$commands_path . "/templates/_auth/views/reset-password.twig.dist");

        // create folder auth
        if (!file_exists(resources_path("views/auth_temp")))
        {
            mkdir(resources_path("views/auth_temp"));
        }

        // create folder account-detail
        if (!file_exists(resources_path("views/auth_temp/account-detail")))
        {
            mkdir(resources_path("views/auth_temp/account-detail"));
        }

        // create folder layouts
        if (!file_exists(resources_path("views/auth_temp/layouts")))
        {
            mkdir(resources_path("views/auth_temp/layouts"));
        }

        // create folder layouts/partial
        if (!file_exists(resources_path("views/auth_temp/layouts/partials")))
        {
            mkdir(resources_path("views/auth_temp/layouts/partials"));
        }

        // create folder templates
        if (!file_exists(resources_path("views/auth_temp/templates")))
        {
            mkdir(resources_path("views/auth_temp/templates"));
        }

        // create folder templates/error-list
        if (!file_exists(resources_path("views/auth_temp/templates/error-list")))
        {
            mkdir(resources_path("views/auth_temp/templates/error-list"));
        }

        // create folder templates/flash-messages
        if (!file_exists(resources_path("views/auth_temp/templates/flash-messages")))
        {
            mkdir(resources_path("views/auth_temp/templates/flash-messages"));
        }

        // create file account-detail/index.twig
        $file = fopen(resources_path("views/auth_temp/account-detail/index.twig"), "w");
        fwrite($file, $account_detail_index);
        fclose($file);

        // create file account-detail/edit.twig
        $file = fopen(resources_path("views/auth_temp/account-detail/edit.twig"), "w");
        fwrite($file, $account_detail_edit);
        fclose($file);

        // create file layouts/partials/nav.twig
        $file = fopen(resources_path("views/auth_temp/layouts/partials/nav.twig"), "w");
        fwrite($file, $layouts_partials_nav);
        fclose($file);

        // create file layouts/app.twig
        $file = fopen(resources_path("views/auth_temp/layouts/app.twig"), "w");
        fwrite($file, $layouts_app);
        fclose($file);

        // create file templates/error-list/bootstrap.twig
        $file = fopen(resources_path("views/auth_temp/templates/error-list/bootstrap.twig"), "w");
        fwrite($file, $templates_error_list_bootstrap);
        fclose($file);

        // create file templates/flash-messages/bootstrap.twig
        $file = fopen(resources_path("views/auth_temp/templates/flash-messages/bootstrap.twig"), "w");
        fwrite($file, $templates_flash_messages_bootstrap);
        fclose($file);

        // create file authenticated-home-page.twig
        $file = fopen(resources_path("views/auth_temp/authenticated-home-page.twig"), "w");
        fwrite($file, $authenticated_home_page);
        fclose($file);

        // create file change-password.twig
        $file = fopen(resources_path("views/auth_temp/change-password.twig"), "w");
        fwrite($file, $change_password);
        fclose($file);

        // create file forgot-password.twig
        $file = fopen(resources_path("views/auth_temp/forgot-password.twig"), "w");
        fwrite($file, $forgot_password);
        fclose($file);

        // create file login.twig
        $file = fopen(resources_path("views/auth_temp/login.twig"), "w");
        fwrite($file, $login);
        fclose($file);

        // create file register.twig
        $file = fopen(resources_path("views/auth_temp/register.twig"), "w");
        fwrite($file, $register);
        fclose($file);

        // create file reset-password.twig
        $file = fopen(resources_path("views/auth_temp/reset-password.twig"), "w");
        fwrite($file, $reset_password);
        fclose($file);

        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/account-detail/index.twig")), $account_detail_index);
        $this->assertFileExists(resources_path("views/auth_temp/account-detail/index.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/account-detail/edit.twig")), $account_detail_edit);
        $this->assertFileExists(resources_path("views/auth_temp/account-detail/edit.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/layouts/partials/nav.twig")), $layouts_partials_nav);
        $this->assertFileExists(resources_path("views/auth_temp/layouts/partials/nav.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/layouts/app.twig")), $layouts_app);
        $this->assertFileExists(resources_path("views/auth_temp/layouts/app.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/templates/error-list/bootstrap.twig")), $templates_error_list_bootstrap);
        $this->assertFileExists(resources_path("views/auth_temp/templates/error-list/bootstrap.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/templates/flash-messages/bootstrap.twig")), $templates_flash_messages_bootstrap);
        $this->assertFileExists(resources_path("views/auth_temp/templates/flash-messages/bootstrap.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/authenticated-home-page.twig")), $authenticated_home_page);
        $this->assertFileExists(resources_path("views/auth_temp/authenticated-home-page.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/change-password.twig")), $change_password);
        $this->assertFileExists(resources_path("views/auth_temp/change-password.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/forgot-password.twig")), $forgot_password);
        $this->assertFileExists(resources_path("views/auth_temp/forgot-password.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/login.twig")), $login);
        $this->assertFileExists(resources_path("views/auth_temp/login.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/register.twig")), $register);
        $this->assertFileExists(resources_path("views/auth_temp/register.twig"));
        $this->assertEquals(file_get_contents(resources_path("views/auth_temp/reset-password.twig")), $reset_password);
        $this->assertFileExists(resources_path("views/auth_temp/reset-password.twig"));
    }

    /**
     * @test
     */
    public function make_controller_template_method()
    {
        $AccountDetailController = strtr(file_get_contents(static::$commands_path . "/templates/_auth/controllers/AccountDetailController.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        $AuthController = strtr(file_get_contents(static::$commands_path . "/templates/_auth/controllers/AuthController.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        $ForgotPasswordController = strtr(file_get_contents(static::$commands_path . "/templates/_auth/controllers/ForgotPasswordController.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        $RegisterController = strtr(file_get_contents(static::$commands_path . "/templates/_auth/controllers/RegisterController.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        $ResetPasswordController = strtr(file_get_contents(static::$commands_path . "/templates/_auth/controllers/ResetPasswordController.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        $register_account_detail_controller = strtr(file_get_contents(static::$commands_path . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "AccountDetailController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_auth_controller = strtr(file_get_contents(static::$commands_path . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "AuthController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_forgot_password_controller = strtr(file_get_contents(static::$commands_path . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "ForgotPasswordController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_register_controller = strtr(file_get_contents(static::$commands_path . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "RegisterController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_reset_password_controller = strtr(file_get_contents(static::$commands_path . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "ResetPasswordController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        if (!file_exists(app_path("Http/Controllers/AuthTemp")))
        {
            mkdir(app_path("Http/Controllers/AuthTemp"));
        }

        // create file AccountDetailController
        $file = fopen(app_path("Http/Controllers/AuthTemp/AccountDetailController.php"), "w");
        fwrite($file, $AccountDetailController);
        fclose($file);

        // create file AuthController
        $file = fopen(app_path("Http/Controllers/AuthTemp/AuthController.php"), "w");
        fwrite($file, $AuthController);
        fclose($file);

        // create file ForgotPasswordController
        $file = fopen(app_path("Http/Controllers/AuthTemp/ForgotPasswordController.php"), "w");
        fwrite($file, $ForgotPasswordController);
        fclose($file);

        // create file RegisterController
        $file = fopen(app_path("Http/Controllers/AuthTemp/RegisterController.php"), "w");
        fwrite($file, $RegisterController);
        fclose($file);

        // create file ResetPasswordController
        $file = fopen(app_path("Http/Controllers/AuthTemp/ResetPasswordController.php"), "w");
        fwrite($file, $ResetPasswordController);
        fclose($file);

        // register the controller AccountDetailController
        $file = fopen(system_path("registered-controllers-temp.php"), "a");
        fwrite($file, $register_account_detail_controller);
        fclose($file);

        // register the controller AuthController
        $file = fopen(system_path("registered-controllers-temp.php"), "a");
        fwrite($file, $register_auth_controller);
        fclose($file);

        // register the controller ForgotPasswordController
        $file = fopen(system_path("registered-controllers-temp.php"), "a");
        fwrite($file, $register_forgot_password_controller);
        fclose($file);

        // register the controller RegisterController
        $file = fopen(system_path("registered-controllers-temp.php"), "a");
        fwrite($file, $register_register_controller);
        fclose($file);

        // register the controller ResetPasswordController
        $file = fopen(system_path("registered-controllers-temp.php"), "a");
        fwrite($file, $register_reset_password_controller);
        fclose($file);

        // check if files created
        $this->assertFileExists(app_path("Http/Controllers/AuthTemp/AccountDetailController.php"));
        $this->assertFileExists(app_path("Http/Controllers/AuthTemp/AuthController.php"));
        $this->assertFileExists(app_path("Http/Controllers/AuthTemp/ForgotPasswordController.php"));
        $this->assertFileExists(app_path("Http/Controllers/AuthTemp/RegisterController.php"));
        $this->assertFileExists(app_path("Http/Controllers/AuthTemp/ResetPasswordController.php"));

        // check if correct content
        $this->assertEquals(file_get_contents(app_path("Http/Controllers/AuthTemp/AccountDetailController.php")), $AccountDetailController);
        $this->assertEquals(file_get_contents(app_path("Http/Controllers/AuthTemp/AuthController.php")), $AuthController);
        $this->assertEquals(file_get_contents(app_path("Http/Controllers/AuthTemp/ForgotPasswordController.php")), $ForgotPasswordController);
        $this->assertEquals(file_get_contents(app_path("Http/Controllers/AuthTemp/RegisterController.php")), $RegisterController);
        $this->assertEquals(file_get_contents(app_path("Http/Controllers/AuthTemp/ResetPasswordController.php")), $ResetPasswordController);

        // check if controllers registered
        $this->assertTrue(strpos(file_get_contents(system_path("registered-controllers-temp.php")), $register_account_detail_controller) !== false);
    }

    /**
     * @test
     */
    public function make_middleware_template_method()
    {
        $guest_template = strtr(file_get_contents(static::$commands_path . "/templates/_auth/middlewares/guest.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);
        $user_template = strtr(file_get_contents(static::$commands_path . "/templates/_auth/middlewares/user.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);
        $valid_to_login_template = strtr(file_get_contents(static::$commands_path . "/templates/_auth/middlewares/valid-to-login.php.dist"), [
            '{{namespace}}' => static::$namespace
        ]);

        if (!file_exists(app_path("Http/Middlewares/AuthTemp")))
        {
            mkdir(app_path("Http/Middlewares/AuthTemp"));
        }

        $file = fopen(app_path("Http/Middlewares/AuthTemp/GuestMiddleware.php"), "w");
        fwrite($file, $guest_template);
        fclose($file);

        $file = fopen(app_path("Http/Middlewares/AuthTemp/UserMiddleware.php"), "w");
        fwrite($file, $user_template);
        fclose($file);
        $file = fopen(app_path("Http/Middlewares/AuthTemp/ValidToLoginMiddleware.php"), "w");
        fwrite($file, $valid_to_login_template);
        fclose($file);

        $this->assertFileExists(app_path("Http/Middlewares/AuthTemp/GuestMiddleware.php"));
        $this->assertFileExists(app_path("Http/Middlewares/AuthTemp/UserMiddleware.php"));
        $this->assertFileExists(app_path("Http/Middlewares/AuthTemp/ValidToLoginMiddleware.php"));

        $this->assertEquals(file_get_contents(app_path("Http/Middlewares/AuthTemp/GuestMiddleware.php")), $guest_template);
        $this->assertEquals(file_get_contents(app_path("Http/Middlewares/AuthTemp/UserMiddleware.php")), $user_template);
        $this->assertEquals(file_get_contents(app_path("Http/Middlewares/AuthTemp/ValidToLoginMiddleware.php")), $valid_to_login_template);
    }

    /**
     * @test
     */
    public function make_config_template_method()
    {
        $config_template = strtr(file_get_contents(static::$commands_path . "/templates/_auth/config.php.dist"), [
            '{{auth_controller}}' => static::$namespace . "\Http\Controllers\Auth\AuthController",
            '{{register_controller}}' => static::$namespace . "\Http\Controllers\Auth\RegisterController",
            '{{forgot_password_controller}}' => static::$namespace . "\Http\Controllers\Auth\ForgotPasswordController",
            '{{reset_password_controller}}' => static::$namespace . "\Http\Controllers\Auth\ResetPasswordController",
            '{{account_detail_controller}}' => static::$namespace . "\Http\Controllers\Auth\AccountDetailController",

            '{{valid_to_login_middleware}}' => static::$namespace . "\Http\Middlewares\Auth\ValidToLoginMiddleware",
            '{{user_middleware}}' => static::$namespace . "\Http\Middlewares\Auth\UserMiddleware",
            '{{guest_middleware}}' => static::$namespace . "\Http\Middlewares\Auth\GuestMiddleware"
        ]);

        $file = fopen(config_path("auth-temp.php"), "w");
        fwrite($file, $config_template);
        fclose($file);

        $this->assertFileExists(config_path("auth-temp.php"));
        $this->assertEquals(file_get_contents(config_path("auth-temp.php")), $config_template);
    }

    /**
     * @test
     */
    public function make_db_template_method()
    {
        $migration_users = file_get_contents(static::$commands_path . "/templates/_auth/db/migrations/20180306091257_create_table_user.php.dist");
        $migration_auth_attempts = file_get_contents(static::$commands_path . "/templates/_auth/db/migrations/20180306091308_create_table_auth_attempts.php.dist");
        $migration_verification_tokens = file_get_contents(static::$commands_path . "/templates/_auth/db/migrations/20180320111202_create_table_verification_tokens.php.dist");
        $seed_users = file_get_contents(static::$commands_path . "/templates/_auth/db/seeds/UserSeeder.php.dist");

        $file = fopen(base_path("db/migrations/20180306091257_create_table_user-temp.php"), "w");
        fwrite($file, $migration_users);
        fclose($file);

        $file = fopen(base_path("db/migrations/20180306091309_create_table_auth_attempts-temp.php"), "w");
        fwrite($file, $migration_auth_attempts);
        fclose($file);

        $file = fopen(base_path("db/migrations/20180320111202_create_table_verification_tokens-temp.php"), "w");
        fwrite($file, $migration_verification_tokens);
        fclose($file);

        $file = fopen(base_path("db/seeds/UserSeeder-temp.php"), "w");
        fwrite($file, $seed_users);
        fclose($file);

        $this->assertFileExists(base_path("db/migrations/20180306091257_create_table_user-temp.php"));
        $this->assertFileExists(base_path("db/migrations/20180306091309_create_table_auth_attempts-temp.php"));
        $this->assertFileExists(base_path("db/migrations/20180320111202_create_table_verification_tokens-temp.php"));
        $this->assertFileExists(base_path("db/seeds/UserSeeder-temp.php"));
        $this->assertEquals(file_get_contents(base_path("db/migrations/20180306091257_create_table_user-temp.php")), $migration_users);
        $this->assertEquals(file_get_contents(base_path("db/migrations/20180306091309_create_table_auth_attempts-temp.php")), $migration_auth_attempts);
        $this->assertEquals(file_get_contents(base_path("db/migrations/20180320111202_create_table_verification_tokens-temp.php")), $migration_verification_tokens);
        $this->assertEquals(file_get_contents(base_path("db/seeds/UserSeeder-temp.php")), $seed_users);
    }

    /**
     * @test
     */
    public function show_important_note_template()
    {
        $this->expectOutputString(strtr(file_get_contents(static::$commands_path . "/templates/_auth/important-note.txt.dist"), [
            '{{namespace}}' => static::$namespace
        ]));

        echo strtr(file_get_contents(static::$commands_path . "/templates/_auth/important-note.txt.dist"), [
            '{{namespace}}' => static::$namespace
        ]);
    }

    public function tearDown()
    {
        $this->tearDownMakeModelTemplate();
        $this->tearDownMakeViewTemplate();
        $this->tearDownMakeControllerTemplate();
        $this->tearDownMakeMiddlewareTemplate();
        $this->tearDownMakeConfigTemplate();
        $this->tearDownMakeDbTemplate();
    }

    private function tearDownMakeModelTemplate()
    {
        if (file_exists(app_path("Models/UserTemp.php")))
        {
            unlink(app_path("Models/UserTemp.php"));
        }
    }

    private function tearDownMakeViewTemplate()
    {
        if (file_exists(resources_path("views/auth_temp")))
        {
            rmdir_recursion(resources_path("views/auth_temp"));
        }
    }

    private function tearDownMakeControllerTemplate()
    {
        if (file_exists(app_path("Http/Controllers/AuthTemp")))
        {
            rmdir_recursion(app_path("Http/Controllers/AuthTemp"));
        }

        if (file_exists(system_path("registered-controllers-temp.php")))
        {
            unlink(system_path("registered-controllers-temp.php"));
        }
    }

    private function tearDownMakeMiddlewareTemplate()
    {
        if (file_exists(app_path("Http/Middlewares/AuthTemp")))
        {
            rmdir_recursion(app_path("Http/Middlewares/AuthTemp"));
        }
    }

    private function tearDownMakeConfigTemplate()
    {
        if (file_exists(config_path("auth-temp.php")))
        {
            unlink(config_path("auth-temp.php"));
        }
    }

    private function tearDownMakeDbTemplate()
    {
        if (file_exists(base_path("db/migrations/20180306091257_create_table_user-temp.php")))
        {
            unlink(base_path("db/migrations/20180306091257_create_table_user-temp.php"));
        }

        if (file_exists(base_path("db/migrations/20180306091309_create_table_auth_attempts-temp.php")))
        {
            unlink(base_path("db/migrations/20180306091309_create_table_auth_attempts-temp.php"));
        }

        if (file_exists(base_path("db/migrations/20180320111202_create_table_verification_tokens-temp.php")))
        {
            unlink(base_path("db/migrations/20180320111202_create_table_verification_tokens-temp.php"));
        }

        if (file_exists(base_path("db/seeds/UserSeeder-temp.php")))
        {
            unlink(base_path("db/seeds/UserSeeder-temp.php"));
        }

    }
}