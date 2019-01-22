<?php

/**
 * Register your api routes on this file.
 */

$app->group('/api', function() {
    api_image_generator($this);

    // jquery validation
    $this->group('/jv', function() {
        $this->get('/email-exist', ["SkeletonAuth\\JqueryValidationController", "emailExist"]);
    });
});


