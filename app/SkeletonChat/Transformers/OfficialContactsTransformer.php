<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonAuthApp\Auth;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\User;

/**
 * Use this if the user authenticated
 */
class OfficialContactsTransformer extends TransformerAbstract
{
    /**
     * [transform description]
     *
     * @param  Contact $contact
     * @return array
     */
    public function transform(Contact $contact)
    {
        $user_contact = User::find($contact->contact_id);

        return [
            'user' => [
                'id' => $user_contact->id,
                'picture' => $user_contact->picture,
                'full_name' => $user_contact->getFullName(),
                'conversation' => $user_contact->conversation(Auth::user()->id)
                                        ->get()
                                        ->last()
            ]
        ];
    }
}
