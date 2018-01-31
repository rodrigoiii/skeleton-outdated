<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class _AuthCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "library:auth {configure : [enable | disable]}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Enable/Disable rodrigoiii/auth library.";

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
        $configure = $input->getArgument('configure');

        try {
            if (!in_array($configure, ["enable", "disable"]))
            {
                throw new \Exception("Invalid argument. Input must be 'enable' or 'disable'.", 1);
            }
            elseif ($configure === "enable" && file_exists(config_path("_auth.php")))
            {
                throw new \Exception("rodrigoiii/auth library is already enable.", 1);
            }
            elseif ($configure === "disable" && !file_exists(config_path("_auth.php")))
            {
                throw new \Exception("rodrigoiii/auth library is already disable.", 1);
            }

            if ($configure === "enable")
            {
                $this->enable();
            }
            else // disable
            {
                $this->disable();
            }
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * Enable the library
     * @return [void]
     */
    private function enable()
    {
        $this->makeModelTemplate();
        $this->makeViewTemplate();
        $this->makeControllerTemplate();
        $this->makeMiddlewareTemplate();
        $this->makeConfigTemplate();

        $this->showImportantNote();
    }

    /**
     * Disable the library
     * @return [void]
     */
    private function disable()
    {
        $this->removeModel();
        $this->removeView();
        $this->removeAndUnregisterController();
        $this->removeMiddleware();
        $this->removeConfiguration();
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

        if (!file_exists(app_path("Models/_Auth")))
        {
            mkdir(app_path("Models/_Auth"));
        }

        $file = fopen(app_path("Models/_Auth/User.php"), "w");
        fwrite($file, $user_template);
        fclose($file);

        $file = fopen(app_path("Models/_Auth/AuthAttempt.php"), "w");
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
        $flash = file_get_contents(__DIR__ . "/templates/_auth/views/templates/flash-message/bootstrap.twig.dist");

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

        if (!file_exists(resources_path("views/_auth/templates")))
        {
            mkdir(resources_path("views/_auth/templates"));
        }

        if (!file_exists(resources_path("views/_auth/templates/flash-message")))
        {
            mkdir(resources_path("views/_auth/templates/flash-message"));
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

        $file = fopen(resources_path("views/_auth/templates/flash-message/bootstrap.twig"), "w");
        fwrite($file, $flash);
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
            '{{pre_controller_namespace}}' => "_Auth\\"
        ]);

        if (!file_exists(app_path("Http/Controllers/_Auth")))
        {
            mkdir(app_path("Http/Controllers/_Auth"));
        }

        // create file
        $file = fopen(app_path("Http/Controllers/_Auth/AuthController.php"), "w");
        fwrite($file, $controller_template);
        fclose($file);

        // register the controller in container
        $file = fopen(settings_path("registered-controllers.php"), "a");
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

        if (!file_exists(app_path("Http/Middlewares/_Auth")))
        {
            mkdir(app_path("Http/Middlewares/_Auth"));
        }

        $file = fopen(app_path("Http/Middlewares/_Auth/Guest.php"), "w");
        fwrite($file, $guest_template);
        fclose($file);

        $file = fopen(app_path("Http/Middlewares/_Auth/User.php"), "w");
        fwrite($file, $user_template);
        fclose($file);
        $file = fopen(app_path("Http/Middlewares/_Auth/ValidToLogin.php"), "w");
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
        $config_template = file_get_contents(__DIR__ . "/templates/_auth/config.php.dist");

        $file = fopen(config_path("_auth.php"), "w");
        fwrite($file, $config_template);
        fclose($file);

        echo "Create Configuration Done." . PHP_EOL;
    }

    /**
     * Uninstall model of auth library
     * @depends disable
     */
    private function removeModel()
    {
        array_map("unlink", glob(app_path("Models/_Auth/*")));
        rmdir(app_path("Models/_Auth")); // remove models
        echo "Removing Model Done." . PHP_EOL;
    }

    /**
     * Uninstall view of auth library
     * @depends disable
     */
    private function removeView()
    {
        rmdir_recursion(resources_path("views/_auth"));
        echo "Removing Model Done." . PHP_EOL;
    }

    /**
     * Uninstall controller of auth library
     * @depends disable
     */
    private function removeAndUnregisterController()
    {
        $registered_controller_file = settings_path("registered-controllers.php");
        $content = file_get_contents($registered_controller_file);
        $registered_template = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "AuthController",
            '{{pre_controller_namespace}}' => "_Auth\\"
        ]);

        file_put_contents($registered_controller_file, str_replace($registered_template, "", $content));
        array_map("unlink", glob(app_path("Http/Controllers/_Auth/*")));
        rmdir(app_path("Http/Controllers/_Auth"));

        echo "Removing Controller Done." . PHP_EOL;
    }

    /**
     * Uninstall middleware of auth library
     * @depends disable
     */
    private function removeMiddleware()
    {
        array_map("unlink", glob(app_path("Http/Middlewares/_Auth/*")));
        rmdir(app_path("Http/Middlewares/_Auth"));
        echo "Removing Middleware Done." . PHP_EOL;
    }

    /**
     * Uninstall configuration of auth library
     * @depends disable
     */
    private function removeConfiguration()
    {
        unlink(config_path("_auth.php"));
        echo "Removing Configuration Done. Remove `'_auth' => config('_auth')` in slim application settings." . PHP_EOL;
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