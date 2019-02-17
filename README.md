# slim-rodrigo v2.0.0-dev

Build awesome application using this light but powerful micro framework.

## Installation
* Create project via composer. Use command `composer require rodrigoiii/framework`.
* Create virtual host then make the folder public as root of your project.
* Then test it in any browser you have available to see if it is working.

## Docs

The docs is not yet available. I will make it if this project get 50 stars.

## Naming Convention

* The controller class should has suffix 'Controller'.
* The middleware class should has suffix 'Middleware'.
* The request class should has suffix 'Request'.
* The command class should has suffix 'Command'.
* When you remove the Validation Rule, delete Validation Exception also.
* When publishing module, make sure you will add that module in `config/app.php` file at `modules` key.

## Creating module in skeleton

The structure of your module must be like below:

```
[module-name]/
|-- src/
    |-- [ModuleName].php (required)
|-- templates/
    |-- app/
        |-- [ModuleName]/
            |-- Controllers/
            |-- Middlewares/
            |-- Requests/
            |-- Validation/
            |-- Console/
                |-- Commands
                |-- templates/
            |-- Debugbar.php
    |-- assets/
        |-- sass/
        |-- js/
    |-- config/
    |-- db/
        |-- migrations/
        |-- seeds/
    |-- views/
|-- composer.json
```

- In `composer.json` append the necessary key value pair.
```json
{
    "autoload": {
        "psr-4": {
            "[ModuleName]\\": "src/",
            "[ModuleName]App\\": "../../../app/[ModuleName]/"
        }
    }
}
```

- In `config/app.php` append "[ModuleName]" in `modules` key. Create if `modules` key not exists and make sure it is array.

And the last one is read the `README.md` file of the module for other setup.

## License
This project is released under the MIT Licence. See the bundled LICENSE file for details.
