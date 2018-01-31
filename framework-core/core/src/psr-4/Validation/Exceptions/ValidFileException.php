<?php

namespace Framework\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ValidFileException extends ValidationException
{
    const ERROR_TYPE = 0;
    const ERROR_SIZE = 1;
    const ERROR_IS_REQUIRED = 2;
    const ERROR_TYPE_SPECIFIC = 3;

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::ERROR_TYPE => "File type must be {{type}}.",
            self::ERROR_SIZE => "File size must be less than {{size}}KB.",
            self::ERROR_IS_REQUIRED => "File is required.",
            self::ERROR_TYPE_SPECIFIC => "File type must be {{type}} and the file extension must be {{specific_type}}.",
        ]
    ];

    public function chooseTemplate()
    {
        $input = $this->getParam('input');

        $files = is_array($input) ? $input : [$input];

        foreach ($files as $file) {
            if (empty($file->getClientFilename()))
                return static::ERROR_IS_REQUIRED;

            $type = explode("/", $file->getClientMediaType());
            $size = $file->getSize();

            if ($type[0] !== $this->getParam('type'))
                return static::ERROR_TYPE;

            if ($size > ($this->getParam('size') * 1000))
                return static::ERROR_SIZE;

            if (!empty($this->getParam('specific_type')) && $this->getParam('specific_type') !== $type[1])
                return static::ERROR_TYPE_SPECIFIC;
        }

        return $mode;
    }
}