<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal;

/**
 * UserTransformer handles turning an internal user model 
 * into a safely exposable user json
 */
class UserTransformer extends Fractal\TransformerAbstract
{
    /**
     * Transform user object into json
     */
    public function transform(User $user) 
    {
        return [
        "id" => $user->id,
        "nickname" => $user->nickname,
        "name" => $user->name,
        "email" => $user->email,
        ];
    }
}
