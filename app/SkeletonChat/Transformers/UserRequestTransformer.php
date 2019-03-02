<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\User;

class UserRequestTransformer extends TransformerAbstract
{
    /**
     * [transform description]
     *
     * @param  Contact $contact
     * @return array
     */
    public function transform(Contact $contact)
    {
        $user = User::find($contact->contact_id);

        return [
            'id' => $contact->id,
            'user' => [
                'picture' => $user->picture,
                'full_name' => $user->getFullName()
            ],
            'requested_at' => $contact->created_at
        ];
    }
}
