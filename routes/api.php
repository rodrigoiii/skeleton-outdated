<?php

/**
 * Register your api routes on this file.
 */

$app->group('/api', function() {
    api_image_generator($this);
});
