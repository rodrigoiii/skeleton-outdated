<?php

namespace SkeletonChatApp\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\ContactsPendingRequestTransformer;
use SkeletonChatApp\Transformers\PendingRequestTransformer;
use SkeletonCore\BaseController;

class ChatApiController extends BaseController
{
    public function searchContacts(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $keyword = $request->getParam('keyword');

        $exclude_contacts = $user->contacts->pluck('contact_id')->toArray();

        array_push($exclude_contacts, $user->id);

        $results = User::search($keyword)
                    ->select(\DB::raw("id, picture, first_name, last_name"))
                    ->whereNotIn('id', $exclude_contacts)->get();

        return $response->withJson([
            'success' => true,
            'data' => $results
        ]);
    }

    public function pendingRequests(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $user_pending_requests = sklt_transformer($user->pendingRequests()->get(), new PendingRequestTransformer)->toArray();
        $contacts_pending_requests = sklt_transformer($user->contactsPendingRequests()->get(), new ContactsPendingRequestTransformer)->toArray();

        return $response->withJson([
            'success' => true,
            'user_pending_requests' => $user_pending_requests['data'],
            'contacts_pending_requests' => $contacts_pending_requests['data']
        ]);
    }

    public function addContact(Request $request, Response $response, $contact_id)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $is_saved = $user->addContact($contact_id);

        // $user_contact = User::find($contact_id);
        // $user_contact->addContact($user->id);

        // $contact = Contact::create([
        //     'contact_id' => $contact_id,
        //     'user_id' => $user->id
        // ]);

        return $response->withJson($is_saved ?
            [
                'success' => true,
                'message' => "Successfully add contact."
            ] :
            [
                'success' => false,
                'message' => "Cannot add contact this time. Please try again later."
            ]
        );
    }
}
