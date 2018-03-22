<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function reply(Message $message, Request $request) {
        $this->validate($request, [
            'body' => 'required',
            'topic_id' => 'notPresent',
            'user_id' => 'notPresent',
        ]);

        $new_message = new Message( array_merge($request->all(), [
            'user_id' => $request->user()->id, 
            'parent_id' => $message->id, 
            'topic_id' => $message->topic_id,
          ]));
        $new_message = $new_message->appendToNode($message);
        $new_message->save();
        return $new_message;
    }

    public function update(Message $message, Request $request) {
      if($message->user_id != $request->user()->id) {
        abort(403);
      }
      $this->validate($request, [
        'topic_id' => 'notPresent',
        'user_id' => 'notPresent',
      ]);
      $message->update($request->all());

      return $message->fresh();
    }
}
