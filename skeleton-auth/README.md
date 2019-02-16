# Skeleton Auth

Rapid authentication for skeleton.

## Need to install manually

 - In the public directory, run `yarn add jquery-validation`.
 - In sponge.config.js, append the following in `scripts.entries_array`:
   ["auth/auth",
    "auth/register",
    "auth/login",
    "auth/account-setting",
    "auth/forgot-password",
    "auth/reset-password",
    "auth/jquery-validation/add-methods"]
 - In `composer.json`, append `{"SkeletonAuthApp\\": "app/SkeletonAuth"}` in autoload.psr-4 // remove this after
 - In `composer.json` append `"SkeletonAuth/src/SkeletonAuth.php"` in autoload.classmap // remove this after
 - In `config/app.php` append "SkeletonAuth" in `modules` key. Create if `modules` key not exists and make sure it is array.

## Route

Register your route in `routes/web.php` file.
```php
(new SkeletonAuth\AuthTrait\Auth($app))->routes();
```

## Api Route

Register your api route in `routes/api.php` file. Make sure its inside of `api`.
```php
(new SkeletonAuth\AuthTrait\Auth($this))->apiRoutes();
```
