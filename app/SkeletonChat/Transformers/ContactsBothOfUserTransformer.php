<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonAuthApp\Auth;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\User;

/**
 * Use this if the user authenticated
 */
class ContactsBothOfUserTransformer extends TransformerAbstract
{
    /**
     * [transform description]
     *
     * @param  Contact $contact
     * @return array
     */
    public function transform(Contact $contact)
    {
        $auth_user = Auth::user();
        $user_contact = User::find($contact->user_id === $auth_user->id ? $contact->owner_id : $contact->user_id);

        return [
            'user' => [
                'id' => $user_contact->id,
                'picture' => $user_contact->picture,
                'full_name' => $user_contact->getFullName(),
                'conversation' => $user_contact->conversation($auth_user->id)
                                        ->get()
                                        ->last()
            ]
        ];
    }
}