<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonChatApp\Models\Contact;

class ContactsPendingRequestTransformer extends TransformerAbstract
{
    /**
     * [transform description]
     *
     * @param  Contact $contact
     * @return array
     */
    public function transform(Contact $contact)
    {
        $user = $contact->user;

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
