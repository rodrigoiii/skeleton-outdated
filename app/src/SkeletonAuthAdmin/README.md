If SkeletonAuthAdmin installed, you need to change the following

1. In file `app/SkeletonAuth/Models/User.php` add method `scopeUser`
```php
public function scopeUser($query)
{
    return $query->where('is_admin', 0);
}
```

And then chain it inside of `findByEmail` method
```php
public static function findByEmail($email)
{
    // return static::where('email', $email)->first(); // before
    return static::user()->where('email', $email)->first(); // after
}
```

2. In file `app/SkeletonAuth/Requests/RegisterRequest.php` chain the rule negation of `adminEmailExist`

```php
// 'email' => v::notEmpty()->email()->not(v::emailExist()), // before
// 'email' => v::notEmpty()->email()->not(v::emailExist())->not(v::adminEmailExist()), // after
```
