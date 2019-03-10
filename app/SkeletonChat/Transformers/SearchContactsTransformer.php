<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonAuthApp\Auth;
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
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'has_request' => !is_null($user->contact_requests()
                                ->notYetAccepted()
                                ->where('to_id', $this->userWhosSearching->id)
                                ->first())
        ];
    }
}
