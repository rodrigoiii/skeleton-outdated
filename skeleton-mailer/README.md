# Skeleton Mailer Library

## Setup & Usage

Create file `mailer.php` inside of `config` folder and copy the snippet below:
```php
<?php

return [
    'host' => app_env('MAIL_HOST', "smtp.mailtrap.io"),
    'port' => app_env('MAIL_PORT', 2525),
    'username' => app_env('MAIL_USERNAME'),
    'password' => app_env('MAIL_PASSWORD'),

    'settings' => [
        'cache' => filter_var(app_env('MAIL_ENABLE_CACHE', false), FILTER_VALIDATE_BOOLEAN) ? storage_path("cache/email-views") : false
    ]
];
```

Obviously on the content, you need to add the following mail configuration on the `.env` file.
```txt
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENABLE_CACHE=
```

Next step is you to enable the mailer command to easily create class snippet.
To do so, open the file `cli` then call the `enableMailer` method.

```php
<?php

#!/usr/bin/env php
<?php

if (PHP_SAPI !== "cli") die; // abort if the usage not via command line

# composer autoload
require __DIR__ . "/vendor/autoload.php";

$appCli = new SkeletonCore\AppCli;
$appCli->enableMailer(); // enable using this
$appCli->run();
```

Show the command list to verify it if mailer command was enable.

`php cli list`

And then use the command `php cli make:mail SampleEmail` to create email class snippet.

Define the subject, senders, receivers and message.
```php
<?php

namespace App\Mailers;

use SkeletonMailer\Mailer;

class SampleEmail extends Mailer
{
    public function __construct()
    {
        $this->subject("Sample Email");
        $this->from(['skeleton@gmail.com' => "Team Skeleton"]);
        $this->to(['receiver@gmail.com' => "I am the receiver"]);
        $this->message("Hello World");

        /**
         * You can use the source file and make it template of the email
         * Just make sure the source file is in the resources/views/emails folder.
         */
        // $this->messageSourceFile("sample-email.twig", ['name' => "Foo Bar"]);
    }
}
```

And the last one, the email is ready to send.
```php
<?php

$sample_email = new SampleEmail;
$number_of_recipient = $sample_email->send();
```

## License

Skeleton Mailer Library is released under the MIT Licence. See the bundled LICENSE file for details.
