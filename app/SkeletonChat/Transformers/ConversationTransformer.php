<?php

namespace SkeletonChatApp\Transformers;

use League\Fractal\TransformerAbstract;
use SkeletonChatApp\Models\Message;

class ConversationTransformer extends TransformerAbstract
{
    /**
     * [transform description]
     *
     * @param  Message $message
     * @return array
     */
    public function transform(Message $message)
    {
        $sender = $message->sender;
        $receiver = $message->receiver;

        return [
            'message' => $message->message,
            'sender' => [
                'id' => $sender->id,
                'picture' => $sender->picture,
                'full_name' => $sender->getFullName(),
                'sent_at' => $message->created_at
            ]
        ];
    }
}
