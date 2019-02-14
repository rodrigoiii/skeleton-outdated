# Skeleton Auth

Rapid authentication for skeleton.

## Need to install manually

 - In the public folder, run `yarn add jquery-validation`.
 - In sponge.config.js, append the following in `scripts.entries_array`:
   - "auth",
   - "auth/register",
   - "auth/login",
   - "auth/account-setting",
   - "auth/forgot-password",
   - "auth/reset-password",
   - "auth/jquery-validation/add-methods"

## Route

Register your route in `routes/web.php`
```php
(new SkeletonAuth\AuthTrait\Auth($app))->routes();
```

## Api Route

Register your api route in `routes/api.php`
```php
(new SkeletonAuth\AuthTrait\Auth($app))->apiRoutes();
```
