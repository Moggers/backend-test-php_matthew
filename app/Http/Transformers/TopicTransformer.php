<?php

namespace App\Http\Transformers;

use App\Models\Topic;
use League\Fractal;

/**
 * TopicTransformer handles turning an internal topic model 
 * into a safely exposable topic json
 */
class TopicTransformer extends Fractal\TransformerAbstract
{
    /**
     * Transform topic object into json
     */
    public function transform(Topic $topic) 
    {
        return [
        'id' => $topic->id,
        'title' => $topic->title,
        'body' => $topic->body,
        'author' => $topic->user_id,
        'message_count' => count($topic->messages),
        'section_id' => $topic->section_id,
        'created' => $topic->created_at->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
        ];
    }
}
