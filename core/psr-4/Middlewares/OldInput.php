<?php

namespace Middlewares;

use Session;

class OldInput extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ( $old_input = Session::get('old_input', true) )
        {
            $new_old_input = [];

            foreach ($old_input as $field => $input) {
                if ( is_array($input) )
                {
                    foreach ($input as $key => $value) {
                        $new_old_input[$field][$key] = $input[$key];
                    }
                }
                else
                {
                    $new_old_input[$field] = $input;
                }
            }

            $this->twigView->getEnvironment()->addGlobal('oldInput', $new_old_input);
            // $this->phpView->addAttribute('old_input', $old_input);
        }

        if ( $old_input_file = Session::get('old_input_file', true) )
        {
            $this->twigView->getEnvironment()->addGlobal('oldInputFile', $old_input_file);
        }

        Session::put('old_input', $request->getParams());
        Session::put('old_input_file', $request->getUploadedFiles());

        return $next($request, $response);
    }
}
