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
        $auth_attempt_template = strtr(file_get_contents(__DIR__ . "/templates/_auth/models/auth-attempt.php.dist"), [
            '{{namespace}}' => $this->namespace
        ]);

        if (!file_exists(app_path("Models/Auth")))
        {
            mkdir(app_path("Models/Auth"));
        }

        $file = fopen(app_path("Models/Auth/User.php"), "w");
        fwrite($file, $user_template);
        fclose($file);

        $file = fopen(app_path("Models/Auth/AuthAttempt.php"), "w");
        fwrite($file, $auth_attempt_template);
        fclose($file);

       echo "Create Model Done." . PHP_EOL;
    }

    /**
     * Make the template for view
     * @depends enable
     */
    private function makeViewTemplate()
    {
        $home = file_get_contents(__DIR__ . "/templates/_auth/views/home.twig.dist");
        $login = file_get_contents(__DIR__ . "/templates/_auth/views/login.twig.dist");
        $nav = file_get_contents(__DIR__ . "/templates/_auth/views/layouts/partials/nav.twig.dist");
        $app = file_get_contents(__DIR__ . "/templates/_auth/views/layouts/app.twig.dist");

        if (!file_exists(resources_path("views/_auth")))
        {
            mkdir(resources_path("views/_auth"));
        }

        if (!file_exists(resources_path("views/_auth/layouts")))
        {
            mkdir(resources_path("views/_auth/layouts"));
        }

        if (!file_exists(resources_path("views/_auth/layouts/partials")))
        {
            mkdir(resources_path("views/_auth/layouts/partials"));
        }

        $file = fopen(resources_path("views/_auth/home.twig"), "w");
        fwrite($file, $home);
        fclose($file);

        $file = fopen(resources_path("views/_auth/login.twig"), "w");
        fwrite($file, $login);
        fclose($file);

        $file = fopen(resources_path("views/_auth/layouts/partials/nav.twig"), "w");
        fwrite($file, $nav);
        fclose($file);

        $file = fopen(resources_path("views/_auth/layouts/app.twig"), "w");
        fwrite($file, $app);
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

        $file = fopen(app_path("Http/Middlewares/Auth/Guest.php"), "w");
        fwrite($file, $guest_template);
        fclose($file);

        $file = fopen(app_path("Http/Middlewares/Auth/User.php"), "w");
        fwrite($file, $user_template);
        fclose($file);
        $file = fopen(app_path("Http/Middlewares/Auth/ValidToLogin.php"), "w");
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
            '{{valid_to_login_middleware_class}}' => $this->namespace . "\Http\Middlewares\Auth\ValidToLogin",
            '{{user_middleware_class}}' => $this->namespace . "\Http\Middlewares\Auth\User",
            '{{guest_middleware_class}}' => $this->namespace . "\Http\Middlewares\Auth\Guest"
        ]);

        $file = fopen(config_path("auth.php"), "w");
        fwrite($file, $config_template);
        fclose($file);

        echo "Create Configuration Done." . PHP_EOL;
    }

    /**
     * Display the important note
     * @depend handle
     * @return [void]
     */
    private function showImportantNote()
    {
        echo file_get_contents(__DIR__ . "/templates/_auth/important-note.txt.dist");
    }
}