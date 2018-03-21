<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(Section $section)
    {
        return Topic::where(['section_id' => $section->id])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param Topic $topic
     * @return Topic
     */
    public function show(Topic $topic)
    {
        return $topic;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @todo We should probably flag new topics for approval.
     * @param  \Illuminate\Http\Request $request
     * @return Topic
     */
    public function store(Section $section, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        return Topic::create(array_merge($request->all(), ['user_id' => $request->user()->id, 'section_id' => $section->id]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @todo Only the owning user or a moderator should be able to update this resource.
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Topic        $topic
     * @return Topic
     */
    public function update(Request $request, Topic $topic)
    {
        $topic->update($request->all());

        return $topic->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topic $topic
     * @throws \Exception
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
    }

    /**
     * Returns a nested "thread" of messages within the topic
     *
     * @param Topic $topic
     * @return array
     */
    public function messages(Topic $topic)
    {
        return Message::where(['topic_id' => $topic->id])->get()->toTree();
    }

    /**
     * Create a new message
     *
     * @param Topic $topic Topic to place the message in
     * @return array Message after creation
     */
    public function postMessage( Topic $topic, Request $request) 
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        $new_message = new Message(array_merge($request->all(), ['user_id' => $request->user()->id, 'topic_id' => $topic->id]));
        $new_message = $new_message->makeRoot();
        $new_message
          ->save();
        return $new_message;
    }
}
