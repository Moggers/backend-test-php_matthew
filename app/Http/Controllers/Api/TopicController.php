<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Message;
use App\Http\Transformers\MessageTransformer;
use App\Http\Transformers\TopicTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Manager;


class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Section $section Show all topics on given section
     *
     * @return Collection
     */
    public function index(Section $section)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new FractalCollection(
                Topic::where(['section_id' => $section->id])->get(),
                new TopicTransformer()
            )
        )->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Topic $topic Topic to show
     *
     * @return Topic
     */
    public function show(Topic $topic)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $topic,
                new TopicTransformer()
            )
        )->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Section                  $section Section to place the new topic on
     * @param \Illuminate\Http\Request $request Containing new topic's data
     *
     * @todo We should probably flag new topics for approval.
     *
     * @return Topic
     */
    public function store(Section $section, Request $request)
    {
        $this->validate(
            $request, [
            'title' => 'required',
            'body' => 'required',
            'section_id' => 'notPresent',
            'user_id' => 'notPresent',
            ]
        );

        $topic = Topic::create(array_merge($request->all(), ['user_id' => $request->user()->id, 'section_id' => $section->id]));
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $topic,
                new TopicTransformer()
            )
        )->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Containing the new topic details
     * @param \App\Models\Topic        $topic   Topic passed in through REST URL param
     *
     * @todo Only the owning user or a moderator should be able to update this resource.
     *
     * @return Topic
     */
    public function update(Request $request, Topic $topic)
    {
        if ($topic->user_id != $request->user()->id) {
            return response()->json('Unauthorized', 403);
        }
        $this->validate(
            $request, [
            'section_id' => 'notPresent',
            'user_id' => 'notPresent',
            ]
        );
        $topic->update($request->all());

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $topic->fresh(),
                new TopicTransformer()
            )
        )->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Topic $topic   Topic to remove
     * @param Request           $request Contains auth information with logged in user
     */
    public function destroy(Topic $topic, Request $request)
    {
        if ($topic->user_id != $request->user()->id) {
            return response()->json('Unauthorized', 403);
        }
        $topic->delete();
    }

    /**
     * Returns a nested "thread" of messages within the topic
     *
     * @param Topic $topic
     *
     * @return array
     */
    public function messages(Topic $topic)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new FractalCollection(
                Message::where(['topic_id' => $topic->id])->get()->toTree(),
                new MessageTransformer()
            )
        )->toArray();
    }

    /**
     * Create a new message
     *
     * @param Topic $topic Topic to place the message in
     *
     * @return array Message after creation
     */
    public function postMessage( Topic $topic, Request $request) 
    {
        $this->validate(
            $request, [
            'body' => 'required',
            'topic_id' => 'notPresent',
            'user_id' => 'notPresent',
            ]
        );

        $new_message = new Message(array_merge($request->all(), ['user_id' => $request->user()->id, 'topic_id' => $topic->id]));
        $new_message->makeRoot();
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
}
