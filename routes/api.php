<?php

/**
 * Register your api routes on this file.
 */

$app->group('/api', function() {
    api_image_generator($this);

    // jquery validation
    $this->group('/jv', function() {
        $this->get('/email-not-exist', function($request) {
            $params = $request->getParams();

            if (isset($params['email']))
            {
                $user = \App\Models\User::findByEmail($params['email']);
                return is_null($user) ? "true" : "false";
            }

            \Log::error("Error: Email must be define on '/jv/email-not-exist' api.");
            return "Parameter is missing!";
        });
    });
});
