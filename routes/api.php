<?php

/**
 * Register your api routes on this file.
 * Use $this instead of $app when registering route.
 * All route here would be prefix of 'api'.
 */
api_image_generator($this);
(new SkeletonAuthApp\Auth($this))->apiRoutes();

$this->group('/chat-application', function() {
    $this->get('/search-contacts', ["SkeletonChatApp\\Api\\ChatApiController", "searchContacts"]);
})
->add("SkeletonChatApp\\Api\\UserApiMiddleware")
->add("XhrMiddleware");
