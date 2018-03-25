<?php

namespace App\Http\Transformers;

use App\Models\Message;
use League\Fractal;

/**
 * MessageTransformer controls the translation of message models
 * to safely exposable message outputs
 */
class MessageTransformer extends Fractal\TransformerAbstract
{
    /**
     * Transform message model into item
     */
    public function transform(Message $message) 
    {
        $children = [];
        $transformer = $this;
        if (is_array($message->children)) {
            foreach ($message->children as $child) {
                $children[] = $transformer->transform($child);
            }
        }
        return [
        'id' => $message->id,
        'body' => $message->body,
        'author' => $message->user_id,
        'created' => $message->created_at->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
        'parent_id' => $message->parent_id,
        'children' => $children,
        ];
    }
}

