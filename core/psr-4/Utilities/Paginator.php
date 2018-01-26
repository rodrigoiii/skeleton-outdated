<?php

namespace Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\UrlWindow;

class Paginator
{
    private $data;

    public function __construct(Model $model, $length, $twig_path)
    {
        $this->data = $model::paginate($length)->setPath($twig_path);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getWindow()
    {
        return UrlWindow::make($this->getData());
    }

    public function getElements()
    {
        $window = $this->getWindow();
        $elements = array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);

        return $elements;
    }
}