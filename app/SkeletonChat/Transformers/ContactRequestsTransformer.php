<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonChatApp\Models\ContactRequest;

class ContactRequestsTransformer extends TransformerAbstract
{
    /**
     * [transform description]
     *
     * @param  ContactRequest $contactRequest
     * @return array
     */
    public function transform(ContactRequest $contactRequest)
    {
        return [
            'id' => $contactRequest->id,
            'by_id' => $contactRequest->by_id,
            'to_id' => $contactRequest->to_id,
            'is_read_by' => $contactRequest->is_read_by,
            'is_read_to' => $contactRequest->is_read_to,
            'is_accepted' => $contactRequest->is_accepted
        ];
    }
}
