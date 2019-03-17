<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonChatApp\Models\User;

class SearchContactsTransformer extends TransformerAbstract
{
    protected $userWhosSearching;

    public function __construct(User $userWhosSearching)
    {
        $this->userWhosSearching = $userWhosSearching;
    }

    /**
     * [transform description]
     *
     * @param  User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'picture' => $user->picture,
            'full_name' => $user->getFullName(),
            'has_request' => !is_null($user->contact_requests()
                                ->notYetAccepted()
                                ->where('to_id', $this->userWhosSearching->id)
                                ->first())
        ];
    }
}
