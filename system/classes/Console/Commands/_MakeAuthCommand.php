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

        if (!file_exists(app_path("Models/Auth")))
        {
            mkdir(app_path("Models/Auth"));
        }

        $file = fopen(app_path("Models/Auth/User.php"), "w");
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
        $authenticated_page = file_get_contents(__DIR__ . "/templates/_auth/views/authenticated-page.twig.dist");
        $login = file_get_contents(__DIR__ . "/templates/_auth/views/login.twig.dist");
        $nav = file_get_contents(__DIR__ . "/templates/_auth/views/partials/nav.twig.dist");

        if (!file_exists(resources_path("views/auth")))
        {
            mkdir(resources_path("views/auth"));
        }

        if (!file_exists(resources_path("views/auth/partials")))
        {
            mkdir(resources_path("views/auth/partials"));
        }

        $file = fopen(resources_path("views/auth/authenticated-page.twig"), "w");
        fwrite($file, $authenticated_page);
        fclose($file);

        $file = fopen(resources_path("views/auth/login.twig"), "w");
        fwrite($file, $login);
        fclose($file);

        $file = fopen(resources_path("views/auth/partials/nav.twig"), "w");
        fwrite($file, $nav);
        fclose($file);

        echo "Create View Done." . PHP_EOL;
    }

    /**
     * Make the template for controller
     * @depends enable
     */
    private function makeControllerTemplate()
    {
        $controller_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/controllers/auth-controller.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);
        $registered_template = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "AuthController",
            '{{pre_controller_namespace}}' => "Auth\\"
        ]);

        if (!file_exists(app_path("Http/Controllers/Auth")))
        {
            mkdir(app_path("Http/Controllers/Auth"));
        }

        // create file
        $file = fopen(app_path("Http/Controllers/Auth/AuthController.php"), "w");
        fwrite($file, $controller_template);
        fclose($file);

        // register the controller in container
        $file = fopen(system_path("registered-controllers.php"), "a");
        fwrite($file, $registered_template);
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
            '{{controller_class}}' => $this->namespace . "\Http\Controllers\Auth\AuthController",
            '{{valid_to_login_middleware_class}}' => $this->namespace . "\Http\Middlewares\Auth\ValidToLoginMiddleware",
            '{{user_middleware_class}}' => $this->namespace . "\Http\Middlewares\Auth\UserMiddleware",
            '{{guest_middleware_class}}' => $this->namespace . "\Http\Middlewares\Auth\GuestMiddleware"
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
        $seed_users = file_get_contents(__DIR__ . "/templates/_auth/db/seeds/UserSeeder.php.dist");

        $file = fopen(base_path("db/migrations/20180306091257_create_table_user.php"), "w");
        fwrite($file, $migration_users);
        fclose($file);

        $file = fopen(base_path("db/migrations/20180306091309_create_table_auth_attempts.php"), "w");
        fwrite($file, $migration_auth_attempts);
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