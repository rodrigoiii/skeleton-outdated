<?php

namespace Framework\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Framework\Utilities\Session;

class Validator
{
    public static function validate($request, $rules)
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            try {
                if (strpos($field, "file") > 0) // check if name have suffix file like 'picture_file'
                {
                    $file = $request->getUploadedFiles();
                    $rule->setName(str_title(ucfirst($field)))->assert($file[$field]);
                }
                else
                {
                    $rule->setName(str_title(ucfirst($field)))->assert($request->getParam($field));
                }
            } catch (NestedValidationException $e) {
                $errors[$field] = $e->getMessages();
            }
        }
        Session::put('errors', $errors);
        return __CLASS__;
    }

    public static function passed()
    {
        return Session::isEmpty('errors');
    }

    public static function failed()
    {
        return !self::passed();
    }

    public static function getErrors()
    {
        return Session::get('errors');
    }
}