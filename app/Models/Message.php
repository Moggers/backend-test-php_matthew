<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kalnoy\Nestedset\NodeTrait;

use App\Models\Topic;

class Message extends Model
{
    use NodeTrait;
    protected $guarded = ['id', 'created_at'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owned_by_user_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
