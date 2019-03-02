<?php

namespace SkeletonChatApp\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\Notification;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\ContactsRequestTransformer;
use SkeletonChatApp\Transformers\UserRequestTransformer;
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

    public function contactRequests(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $user_requests = sklt_transformer($user->userRequests()->get(), new UserRequestTransformer)->toArray();
        $contact_requests = sklt_transformer($user->contactRequests()->get(), new ContactsRequestTransformer)->toArray();

        return $response->withJson([
            'success' => true,
            'user_requests' => $user_requests['data'],
            'contact_requests' => $contact_requests['data']
        ]);
    }

    public function addContact(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $contact_id = $request->getParam('contact_id'); // Column contact_id of contacts table

        $user = User::findByLoginToken($login_token);

        $contact_type = $user->addContact($contact_id);

        switch ($contact_type) {
            case Notification::TYPE_ACCEPTED:
                $data = [
                    'success' => true,
                    'message' => "Successfully add contact.",
                    'type' => Notification::TYPE_ACCEPTED
                ];
                break;

            case Notification::TYPE_REQUESTED:
                $data = [
                    'success' => true,
                    'message' => "Successfully send request.",
                    'type' => Notification::TYPE_REQUESTED
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

    /**
     * @param Request  $request
     * @param Response $response
     * @param integer   $contact_id id of table contacts
     */
    public function removeRequest(Request $request, Response $response, $contact_id)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);
        $contact = Contact::find($contact_id);

        if (!is_null($contact))
        {
            $is_deleted = $user->removeUserRequest($contact->contact_id);

            if ($is_deleted)
            {
                $notification = $user->findNotification($contact->contact_id);

                return $response->withJson([
                    'success' => true,
                    'message' => "Successfully remove user request.",
                    'notification_id' => !is_null($notification) ? $notification->id : null
                ]);
            }
        }

        return $response->withJson([
            'success' => false,
            'message' => "Cannot remove user request this time. Please try again later."
        ]);
    }

    public function removeNotification(Request $request, Response $response, $notification_id)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $notification = Notification::find($notification_id);
        $is_deleted = !is_null($notification) ? $notification->delete() : false;

        return $response->withJson(
            $is_deleted ?
            [
                'success' => true,
                'message' => "Successfully remove request notification."
            ] :
            [
                'success' => false,
                'message' => "Cannot remove user request this time. Please try again later."
            ]
        );
    }
}
