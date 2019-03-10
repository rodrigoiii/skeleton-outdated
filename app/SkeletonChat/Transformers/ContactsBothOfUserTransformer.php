<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonAuthApp\Auth;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\User;

class ContactsBothOfUserTransformer extends TransformerAbstract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * [transform description]
     *
     * @param  Contact $contact
     * @return array
     */
    public function transform(Contact $contact)
    {
        $userContact = User::find($contact->user_id === $this->user->id ? $contact->owner_id : $contact->user_id);

        return [
            'user' => [
                'id' => $userContact->id,
                'picture' => $userContact->picture,
                'full_name' => $userContact->getFullName(),
                'conversation' => $userContact->conversation($this->user->id)
                                        ->get()
                                        ->last()
            ]
        ];
    }
}
