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
    $this->post('/add-contact', ["SkeletonChatApp\\Api\\ChatApiController", "addContact"]);
    $this->delete('/remove-request/{contact_id}', ["SkeletonChatApp\\Api\\ChatApiController", "removeRequest"]);
    $this->delete('/remove-notification/{notification_id}', ["SkeletonChatApp\\Api\\ChatApiController", "removeNotification"]);
})
->add("SkeletonChatApp\\Api\\UserApiMiddleware")
->add("XhrMiddleware");
