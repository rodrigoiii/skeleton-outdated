<?php

namespace Console\Commands;

class NotificationCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:notification {notification}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create notification class template.";

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

        try {
            if (!ctype_upper($notification[0]))
                throw new \Exception("Error: Invalid Notification. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("Notifications/{$notification}.php")))
                throw new \Exception("Error: The Notification is already created.", 1);

            $output->writeln($this->makeTemplate($notification) ? "Successfully created." : "File not created. Check the file path.");
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
    private function makeTemplate($notification)
    {
        $file = core_path("psr-4/Console/Commands/templates/notification.php.dist");
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "{$this->namespace}",
                '{{notification}}' => $notification,
            ]);

            $file_path = app_path("Notifications/{$notification}.php");

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
}