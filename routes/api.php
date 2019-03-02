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
    $this->get('/contact-requests', ["SkeletonChatApp\\Api\\ChatApiController", "contactRequests"]);
    $this->post('/add-contact/{contact_id}', ["SkeletonChatApp\\Api\\ChatApiController", "addContact"]);
})
->add("SkeletonChatApp\\Api\\UserApiMiddleware")
->add("XhrMiddleware");
