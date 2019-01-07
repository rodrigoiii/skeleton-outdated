<?php

/**
 * Register your api routes on this file.
 */

$app->group('/api', function() {
    api_image_generator($this);

    // jquery validation
    $this->group('/jv', function() {
        $this->get('/email-exist', function($request) {
            $params = $request->getParams();
            $invert = isset($params['invert']);

            if (isset($params['email']))
            {
                $user = \App\Models\User::findByEmail($params['email']);

                $result = !$invert ? !is_null($user) : is_null($user);
                return $result ? "true" : "false";
            }

            \Log::error("Error: Email must be define on '/jv/email-exist' api.");
            return "Parameter is missing!";
        });
    });
});


