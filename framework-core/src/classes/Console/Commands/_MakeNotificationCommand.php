<?php

namespace FrameworkCore\Console\Commands;

use FrameworkCore\BaseCommand;

class _MakeNotificationCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:notification {notification} {--t|email_template_name= : email template}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create notification class template. (special command)";

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
        $notification = $input->getArgument('notification');
        $email_template_name = $input->getOption('email_template_name');

        try {
            if (!ctype_upper($notification[0]))
                throw new \Exception("Error: Invalid Notification. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("Notifications/{$notification}.php")))
                throw new \Exception("Error: The Notification is already created.", 1);

            if (!file_exists(app_path("Notifications")))
            {
                mkdir(app_path("Notifications"));
            }

            if (!file_exists(config_path("notification-slim.php")))
            {
                $output->writeln($this->makeConfigTemplate() ? "Successfully created config template." : "File not created. Check the file path.");
                $this->showImportantNote();
            }

            $output->writeln($this->makeNotificationTemplate($notification, $email_template_name) ? "Successfully created notification and email template." : "File not created. Check the file path.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * [Create notification template]
     * @depends handle
     * @param  [string] $notification [notification name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeNotificationTemplate($notification, $email_template_name)
    {
        $file = __DIR__ . "/templates/_notification/notification.php.dist";
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => $this->namespace,
                '{{notification}}' => $notification,
            ]);

            $file_path = app_path("Notifications/{$notification}.php");

            $file = fopen($file_path, "w");
            fwrite($file, $template);
            fclose($file);

            return file_exists($file_path) && (!is_null($email_template_name) ? $this->makeEmailTemplate($email_template_name) : true);
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }

    /**
     * [Create configuration template]
     * @depends handle
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeConfigTemplate()
    {
        $file = __DIR__ . "/templates/_notification/config.php.dist";
        if (file_exists($file))
        {
            $template = file_get_contents($file);

            $file_path = config_path("notification-slim.php");

            $file = fopen($file_path, "w");
            fwrite($file, $template);
            fclose($file);

            return file_exists($file_path);
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }

    /**
     * [Create email template]
     * @depends makeNotificationTemplate
     * @param  [string] $email_template_name [email template filename]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeEmailTemplate($email_template_name)
    {
        $file = __DIR__ . "/templates/_notification/email.twig.dist";
        if (file_exists($file))
        {
            $template = file_get_contents($file);

            $file_path = resources_path("views/emails/{$email_template_name}.twig");

            if (!file_exists(resources_path("views/emails")))
            {
                mkdir(resources_path("views/emails"));
            }

            if (file_exists($file_path))
            {
                echo "{$email_template_name}.twig is already exist. You need to create email template manually at resources/views/emails folder." . PHP_EOL;
                return true;
            }

            $file = fopen($file_path, "w");
            fwrite($file, $template);
            fclose($file);

            return file_exists($file_path);
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }

    /**
     * [Show important note]
     * @depends handle
     * @return [void]
     */
    private function showImportantNote()
    {
        $file = __DIR__ . "/templates/_notification/important-note.txt.dist";
        if (file_exists($file))
        {
            $template = file_get_contents($file);

            echo $template . PHP_EOL;
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }
}