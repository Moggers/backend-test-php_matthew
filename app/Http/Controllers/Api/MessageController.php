<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\MessageTransformer;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Manager;

class MessageController extends Controller
{
    public function reply(Message $message, Request $request) 
    {
        $this->validate(
            $request, [
            'body' => 'required',
            'topic_id' => 'notPresent',
            'user_id' => 'notPresent',
            'is_highlight' => 'notPresent',
            ]
        );

        $new_message = new Message(
            array_merge(
                $request->all(), [
                'user_id' => $request->user()->id, 
                'parent_id' => $message->id, 
                'topic_id' => $message->topic_id,
                ]
            )
        );
        $new_message = $new_message->appendToNode($message);
        $new_message->save();

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $new_message,
                new MessageTransformer()
            )
        )->toArray();
    }

    public function highlight(Message $message, Request $request) 
    {
        if ($request->user()->id == $message->topic->user_id) {
            $message->update(['is_highlight' => $request->highlight]);

            $manager = new Manager();
            $manager->setSerializer(new JsonApiSerializer());
            return $manager->createData(
                new Item(
                    $message->fresh(),
                    new MessageTransformer()
                )
            )->toArray();
        } else {
            response()->json(['Unauthorized', 403]);
        }

    }

    public function update(Message $message, Request $request) 
    {
        if ($message->user_id != $request->user()->id) {
            abort(403);
        }
        $this->validate(
            $request, [
            'topic_id' => 'notPresent',
            'user_id' => 'notPresent',
            ]
        );
        $message->update($request->all());

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $message->fresh(),
                new MessageTransformer()
            )
        )->toArray();
    }
}
