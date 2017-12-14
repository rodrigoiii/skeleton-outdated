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
        $this->namespace = _env('APP_NAMESPACE', "App");

        parent::__construct($this->signature, $this->description);
    }

    /**
     * Execute the console command
     */
    public function handle($input, $output)
    {
        $notification = $input->getArgument('notification');

        if (!ctype_upper($notification[0]))
        {
            $output->writeln("Error: Invalid Notification. It must be PascalCase.");
            exit;
        }
        elseif (file_exists(config('path.notification.base') . "/{$notification}.php"))
        {
            $output->writeln("Error: The Notification is already created.");
            exit;
        }

        $output->writeln($this->makeTemplate($notification) ? "Successfully created." : "File not created. Check the file path.");
    }

    /**
     * [Create notification template]
     * @depends handle
     * @param  [string] $notification [notification name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($notification)
    {
        $file = config('path.console.foundation_command_base') . "/templates/notification.php.dist";
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "{$this->namespace}",
                '{{notification}}' => $notification,
            ]);

            $file_path = config('path.notification.base') . "/{$notification}.php";

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