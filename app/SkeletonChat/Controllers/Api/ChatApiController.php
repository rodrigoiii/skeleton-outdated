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

        $official_contact_ids = $user->officialContactsOfEachOther()->pluck('contact_id')->toArray();
        $user_requests_ids = $user->userRequests()->pluck('contact_id')->toArray();

        $ignore_user_ids = array_flatten([$official_contact_ids, $user_requests_ids, $user->id]);

        $results = User::search($keyword)
                    ->select(\DB::raw("id, picture, first_name, last_name"))
                    ->whereNotIn('id', $ignore_user_ids)
                    ->get();

        return $response->withJson([
            'success' => true,
            'data' => $results
        ]);
    }

    public function pendingRequests(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $user_requests = sklt_transformer($user->userRequests()->get(), new PendingRequestTransformer)->toArray();
        $contact_requests = sklt_transformer($user->contactRequests()->get(), new ContactsPendingRequestTransformer)->toArray();

        return $response->withJson([
            'success' => true,
            'user_requests' => $user_requests['data'],
            'contact_requests' => $contact_requests['data']
        ]);
    }

    public function addContact(Request $request, Response $response, $contact_id)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $contact_type = $user->addContact($contact_id);

        switch ($contact_type) {
            case Contact::TYPE_ACCEPTED:
                $data = [
                    'success' => true,
                    'message' => "Successfully add contact.",
                    'type' => Contact::TYPE_ACCEPTED
                ];
                break;

            case Contact::TYPE_REQUESTED:
                $data = [
                    'success' => true,
                    'message' => "Successfully send request.",
                    'type' => Contact::TYPE_REQUESTED
                ];
                break;

            default:
                $data = [
                    'success' => false,
                    'message' => "Cannot add contact this time. Please try again later."
                ];
                break;
        }

        return $response->withJson($data);
    }
}
