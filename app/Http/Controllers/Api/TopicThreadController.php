<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class TopicThreadController extends Controller
{

    /**
     * Returns a nested "thread" of messages within the topic
     *
     * @param Topic $topic
     * @return array
     */
    public function show(Topic $topic)
    {
        return $this->outputMessages($topic->messages()
            ->where('parent_id', 0));
    }

    /**
     * @param Collection|Relation $messages
     * @return array
     */
    protected function outputMessages($messages)
    {
        $output = [];
        foreach ($messages->get() as $message) {
            $data = $message->toArray();
            $data['children'] = $this->outputMessages(Message::where('parent_id', $message->id));
            $output[] = $data;
        }

        return $output;
    }
}
