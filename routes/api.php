<?php

/**
 * Register your api routes on this file.
 * Use $this instead of $app when registering route.
 * All route here would be prefix of 'api'.
 */
api_image_generator($this);
(new SkeletonAuthApp\Auth($this))->apiRoutes();

$this->group('/chat-application', function() {
    $this->put('/read-messages/{chatting_to_id}', ["SkeletonChatApp\\Api\\ChatApiController", "readMessages"]);
    $this->get('/fetch-conversation/{chatting_to_id}', ["SkeletonChatApp\\Api\\ChatApiController", "fetchConversation"]);
    $this->post('/send-message/{chatting_to_id}', ["SkeletonChatApp\\Api\\ChatApiController", "sendMessage"]);
    $this->get('/load-more-messages/{chatting_to_id}', ["SkeletonChatApp\\Api\\ChatApiController", "loadMoreMessages"]);

    $this->get('/search-contacts', ["SkeletonChatApp\\Api\\ChatApiController", "searchContacts"]);
    $this->get('/contact-requests', ["SkeletonChatApp\\Api\\ChatApiController", "contactRequests"]);
    $this->post('/add-contact-request', ["SkeletonChatApp\\Api\\ChatApiController", "addContactRequest"]);
    // $this->delete('/remove-request/{contact_id}', ["SkeletonChatApp\\Api\\ChatApiController", "removeRequest"]);

    // $this->put('/read-notification', ["SkeletonChatApp\\Api\\ChatApiController", "readNotification"]);
    // $this->delete('/remove-notification/{notification_id}', ["SkeletonChatApp\\Api\\ChatApiController", "removeNotification"]);
})
->add("SkeletonChatApp\\Api\\UserApiMiddleware")
->add("XhrMiddleware");
