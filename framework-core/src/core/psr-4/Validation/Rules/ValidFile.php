<?php

namespace Framework\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class ValidFile extends AbstractRule
{
    public $type;
    public $size;
    public $is_required;
    public $specific_type;

    public function __construct($type, $size = 5000, $is_required = false, $specific_type = "")
    {
        $this->type = $type;
        $this->size = $size; // represent as kilobytes
        $this->is_required = $is_required;
        $this->specific_type = $specific_type;
    }

    public function validate($input)
    {
        $files = is_array($input) ? $input : [$input];

        foreach ($files as $file) {
            if (empty($file->getClientFilename()))
                return !$this->is_required;

            $type = explode("/", $file->getClientMediaType());
            $size = $file->getSize();

            if ($type[0] !== $this->type || $size > ($this->size * 1000))
                return false;

            if (!empty($this->specific_type) && $this->specific_type !== $type[1])
                return false;
        }

        return true;
    }
}