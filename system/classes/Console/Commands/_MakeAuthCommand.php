<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class _MakeAuthCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:auth";

    /**
     * Console command description
     * @var string
     */
    private $description = "Make authentication like a boom!.";

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        $this->namespace = config("app.namespace");

        parent::__construct($this->signature, $this->description);
    }

    /**
     * Execute the console command
     */
    public function handle($input, $output)
    {
        try {
            if (file_exists(config_path("auth.php")))
            {
                throw new \Exception("Authentication is already created.", 1);
            }

            $this->makeModelTemplate();
            $this->makeViewTemplate();
            $this->makeControllerTemplate();
            $this->makeMiddlewareTemplate();
            $this->makeConfigTemplate();
            $this->makeDbTemplate();

            $this->showImportantNote();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * Make the template for model
     * @depends enable
     */
    private function makeModelTemplate()
    {
        $user_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/models/user.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        $file = fopen(app_path("Models/User.php"), "w");
        fwrite($file, $user_template);
        fclose($file);

        echo "Create Model Done." . PHP_EOL;
    }

    /**
     * Make the template for view
     * @depends enable
     */
    private function makeViewTemplate()
    {
        $account_detail_index = file_get_contents(__DIR__ . "/templates/_auth/views/account-detail/index.twig.dist");
        $account_detail_edit = file_get_contents(__DIR__ . "/templates/_auth/views/account-detail/edit.twig.dist");

        $layouts_partials_nav = file_get_contents(__DIR__ . "/templates/_auth/views/layouts/partials/nav.twig.dist");
        $layouts_app = file_get_contents(__DIR__ . "/templates/_auth/views/layouts/app.twig.dist");

        $templates_error_list_bootstrap = file_get_contents(__DIR__ . "/templates/_auth/views/templates/error-list/bootstrap.twig.dist");
        $templates_flash_messages_bootstrap = file_get_contents(__DIR__ . "/templates/_auth/views/templates/flash-messages/bootstrap.twig.dist");

        $authenticated_home_page = file_get_contents(__DIR__ . "/templates/_auth/views/authenticated-home-page.twig.dist");
        $change_password = file_get_contents(__DIR__ . "/templates/_auth/views/change-password.twig.dist");
        $forgot_password = file_get_contents(__DIR__ . "/templates/_auth/views/forgot-password.twig.dist");
        $login = file_get_contents(__DIR__ . "/templates/_auth/views/login.twig.dist");
        $register = file_get_contents(__DIR__ . "/templates/_auth/views/register.twig.dist");
        $reset_password = file_get_contents(__DIR__ . "/templates/_auth/views/reset-password.twig.dist");

        // create folder auth
        if (!file_exists(resources_path("views/auth")))
        {
            mkdir(resources_path("views/auth"));
        }

        // create folder account-detail
        if (!file_exists(resources_path("views/auth/account-detail")))
        {
            mkdir(resources_path("views/auth/account-detail"));
        }

        // create folder layouts
        if (!file_exists(resources_path("views/auth/layouts")))
        {
            mkdir(resources_path("views/auth/layouts"));
        }

        // create folder layouts/partial
        if (!file_exists(resources_path("views/auth/layouts/partials")))
        {
            mkdir(resources_path("views/auth/layouts/partials"));
        }

        // create folder templates
        if (!file_exists(resources_path("views/auth/templates")))
        {
            mkdir(resources_path("views/auth/templates"));
        }

        // create folder templates/error-list
        if (!file_exists(resources_path("views/auth/templates/error-list")))
        {
            mkdir(resources_path("views/auth/templates/error-list"));
        }

        // create folder templates/flash-messages
        if (!file_exists(resources_path("views/auth/templates/flash-messages")))
        {
            mkdir(resources_path("views/auth/templates/flash-messages"));
        }

        // create file account-detail/index.twig
        $file = fopen(resources_path("views/auth/account-detail/index.twig"), "w");
        fwrite($file, $account_detail_index);
        fclose($file);

        // create file account-detail/edit.twig
        $file = fopen(resources_path("views/auth/account-detail/edit.twig"), "w");
        fwrite($file, $account_detail_edit);
        fclose($file);

        // create file layouts/partials/nav.twig
        $file = fopen(resources_path("views/auth/layouts/partials/nav.twig"), "w");
        fwrite($file, $layouts_partials_nav);
        fclose($file);

        // create file layouts/app.twig
        $file = fopen(resources_path("views/auth/layouts/app.twig"), "w");
        fwrite($file, $layouts_app);
        fclose($file);

        // create file templates/error-list/bootstrap.twig
        $file = fopen(resources_path("views/auth/templates/error-list/bootstrap.twig"), "w");
        fwrite($file, $templates_error_list_bootstrap);
        fclose($file);

        // create file templates/flash-messages/bootstrap.twig
        $file = fopen(resources_path("views/auth/templates/flash-messages/bootstrap.twig"), "w");
        fwrite($file, $templates_flash_messages_bootstrap);
        fclose($file);

        // create file authenticated-home-page.twig
        $file = fopen(resources_path("views/auth/authenticated-home-page.twig"), "w");
        fwrite($file, $authenticated_home_page);
        fclose($file);

        // create file change-password.twig
        $file = fopen(resources_path("views/auth/change-password.twig"), "w");
        fwrite($file, $change_password);
        fclose($file);

        // create file forgot-password.twig
        $file = fopen(resources_path("views/auth/forgot-password.twig"), "w");
        fwrite($file, $forgot_password);
        fclose($file);

        // create file login.twig
        $file = fopen(resources_path("views/auth/login.twig"), "w");
        fwrite($file, $login);
        fclose($file);

        // create file register.twig
        $file = fopen(resources_path("views/auth/register.twig"), "w");
        fwrite($file, $register);
        fclose($file);

        // create file reset-password.twig
        $file = fopen(resources_path("views/auth/reset-password.twig"), "w");
        fwrite($file, $reset_password);
        fclose($file);

        echo "Create View Done." . PHP_EOL;
    }

    /**
     * Make the template for controller
     * @depends enable
     */
    private function makeControllerTemplate()
    {
        $AccountDetailController = strtr(file_get_contents(__DIR__ . "/templates/_auth/controllers/AccountDetailController.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        $AuthController = strtr(file_get_contents(__DIR__ . "/templates/_auth/controllers/AuthController.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        $ForgotPasswordController = strtr(file_get_contents(__DIR__ . "/templates/_auth/controllers/ForgotPasswordController.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        $RegisterController = strtr(file_get_contents(__DIR__ . "/templates/_auth/controllers/RegisterController.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        $ResetPasswordController = strtr(file_get_contents(__DIR__ . "/templates/_auth/controllers/ResetPasswordController.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        $register_account_detail_controller = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "AccountDetailController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_auth_controller = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "AuthController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_forgot_password_controller = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "ForgotPasswordController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_register_controller = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "RegisterController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        $register_reset_password_controller = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "ResetPasswordController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        if (!file_exists(app_path("Http/Controllers/Auth")))
        {
            mkdir(app_path("Http/Controllers/Auth"));
        }

        // create file AccountDetailController
        $file = fopen(app_path("Http/Controllers/Auth/AccountDetailController.php"), "w");
        fwrite($file, $AccountDetailController);
        fclose($file);

        // create file AuthController
        $file = fopen(app_path("Http/Controllers/Auth/AuthController.php"), "w");
        fwrite($file, $AuthController);
        fclose($file);

        // create file ForgotPasswordController
        $file = fopen(app_path("Http/Controllers/Auth/ForgotPasswordController.php"), "w");
        fwrite($file, $ForgotPasswordController);
        fclose($file);

        // create file RegisterController
        $file = fopen(app_path("Http/Controllers/Auth/RegisterController.php"), "w");
        fwrite($file, $RegisterController);
        fclose($file);

        // create file ResetPasswordController
        $file = fopen(app_path("Http/Controllers/Auth/ResetPasswordController.php"), "w");
        fwrite($file, $ResetPasswordController);
        fclose($file);

        // register the controller AccountDetailController
        $file = fopen(system_path("registered-controllers.php"), "a");
        fwrite($file, $register_account_detail_controller);
        fclose($file);

        // register the controller AuthController
        $file = fopen(system_path("registered-controllers.php"), "a");
        fwrite($file, $register_auth_controller);
        fclose($file);

        // register the controller ForgotPasswordController
        $file = fopen(system_path("registered-controllers.php"), "a");
        fwrite($file, $register_forgot_password_controller);
        fclose($file);

        // register the controller RegisterController
        $file = fopen(system_path("registered-controllers.php"), "a");
        fwrite($file, $register_register_controller);
        fclose($file);

        // register the controller ResetPasswordController
        $file = fopen(system_path("registered-controllers.php"), "a");
        fwrite($file, $register_reset_password_controller);
        fclose($file);

        echo "Create Controller Done." . PHP_EOL;
    }

    /**
     * Make the template for middleware
     * @depends enable
     */
    private function makeMiddlewareTemplate()
    {
        $guest_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/middlewares/guest.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);
        $user_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/middlewares/user.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);
        $valid_to_login_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/middlewares/valid-to-login.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        if (!file_exists(app_path("Http/Middlewares/Auth")))
        {
            mkdir(app_path("Http/Middlewares/Auth"));
        }

        $file = fopen(app_path("Http/Middlewares/Auth/GuestMiddleware.php"), "w");
        fwrite($file, $guest_template);
        fclose($file);

        $file = fopen(app_path("Http/Middlewares/Auth/UserMiddleware.php"), "w");
        fwrite($file, $user_template);
        fclose($file);
        $file = fopen(app_path("Http/Middlewares/Auth/ValidToLoginMiddleware.php"), "w");
        fwrite($file, $valid_to_login_template);
        fclose($file);

        echo "Create Middleware Done." . PHP_EOL;
    }

    /**
     * Make the template for model
     * @depends enable
     */
    private function makeConfigTemplate()
    {
        $config_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/config.php.dist"), [
            '{{auth_controller}}' => $this->namespace . "\Http\Controllers\Auth\AuthController",
            '{{register_controller}}' => $this->namespace . "\Http\Controllers\Auth\RegisterController",
            '{{forgot_password_controller}}' => $this->namespace . "\Http\Controllers\Auth\ForgotPasswordController",
            '{{reset_password_controller}}' => $this->namespace . "\Http\Controllers\Auth\ResetPasswordController",
            '{{account_detail_controller}}' => $this->namespace . "\Http\Controllers\Auth\AccountDetailController",

            '{{valid_to_login_middleware}}' => $this->namespace . "\Http\Middlewares\Auth\ValidToLoginMiddleware",
            '{{user_middleware}}' => $this->namespace . "\Http\Middlewares\Auth\UserMiddleware",
            '{{guest_middleware}}' => $this->namespace . "\Http\Middlewares\Auth\GuestMiddleware"
        ]);

        $file = fopen(config_path("auth.php"), "w");
        fwrite($file, $config_template);
        fclose($file);

        echo "Create Configuration Done." . PHP_EOL;
    }

    /**
     * Make database template
     * @depends enable
     */
    private function makeDbTemplate()
    {
        $migration_users = file_get_contents(__DIR__ . "/templates/_auth/db/migrations/20180306091257_create_table_user.php.dist");
        $migration_auth_attempts = file_get_contents(__DIR__ . "/templates/_auth/db/migrations/20180306091308_create_table_auth_attempts.php.dist");
        $migration_verification_tokens = file_get_contents(__DIR__ . "/templates/_auth/db/migrations/20180320111202_create_table_verification_tokens.php.dist");
        $seed_users = file_get_contents(__DIR__ . "/templates/_auth/db/seeds/UserSeeder.php.dist");

        $file = fopen(base_path("db/migrations/20180306091257_create_table_user.php"), "w");
        fwrite($file, $migration_users);
        fclose($file);

        $file = fopen(base_path("db/migrations/20180306091309_create_table_auth_attempts.php"), "w");
        fwrite($file, $migration_auth_attempts);
        fclose($file);

        $file = fopen(base_path("db/migrations/20180320111202_create_table_verification_tokens.php"), "w");
        fwrite($file, $migration_verification_tokens);
        fclose($file);

        $file = fopen(base_path("db/seeds/UserSeeder.php"), "w");
        fwrite($file, $seed_users);
        fclose($file);
    }

    /**
     * Display the important note
     * @depend handle
     * @return [void]
     */
    private function showImportantNote()
    {
        echo PHP_EOL;
        echo strtr(file_get_contents(__DIR__ . "/templates/_auth/important-note.txt.dist"), [
            '{{namespace}}' => $this->namespace
        ]);
    }
}