<?php

/**
 * Register your api routes on this file.
 */

$app->group('/api', function() {
    // jquery validation
    $this->group('/jv', function() {
        $this->get('/email-exist', ["SkeletonAuth\\JqueryValidationController", "emailExist"]);
    });
});


