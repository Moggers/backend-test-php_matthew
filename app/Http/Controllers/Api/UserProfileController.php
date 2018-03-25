<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Manager;

/**
 * User Profile class
 * 
 * This class handles updating and fetching of the user profile information.
 * It specifically does not handle creation of users, or management of their
 * login details, that is handled by the App\Http\Controllers\Auth\AuthController
 */
class UserProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request Request information containing the auth'd user to show
     *
     * @todo Only return fields appropriate to profile; nickname, bio etc.
     *
     * @return User
     */
    public function showSelf(Request $request)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());

        return $manager->createData(
            new Item(
                $request->user(), 
                new UserTransformer
            )
        )->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user User to retrieve from database and show to user
     *
     * @todo Only return fields appropriate to profile; nickname, bio etc.
     *
     * @return User Retrieved user
     */
    public function show(User $user)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $user, 
                new UserTransformer
            )
        )->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Source of the useru pdate details
     *
     * @todo Only return fields appropriate to profile; nickname, bio etc.
     * @todo Only the owning user should be able to update the profile.
     *
     * @return User
     */
    public function update(Request $request)
    {
        $this->validate(
            $request, [
            'nickname' => 'unique:users',
            'remember_token' => 'notPresent',
            'password' => 'notPresent',
            ]
        );
        $request->user()->update($request->all());

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $request->user()->fresh(), 
                new UserTransformer
            )
        )->toArray();
    }

    /**
     * Set the user's profile
     *
     * @param Request $request contains the authed user and the file input
     */
    public function setAvatar(Request $request)
    {
        $request->validate(
            [
            'avatar' => 'required|image'
            ]
        );

        $imageName = time() . '-' . $request->user()->id . '.' . $request->avatar->getClientOriginalExtension();
        $request->avatar->move(public_path('images/avatars'), $imageName);
        $user = $request->user();
        $avatar_path = 'images/avatars/' . $imageName;
        $user->update(['avatar' => $avatar_path]);

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $user->fresh(), 
                new UserTransformer
            )
        )->toArray();
    }
}
