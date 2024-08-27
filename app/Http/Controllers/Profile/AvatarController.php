<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    // Updates the user's avatar
    public function update(UpdateAvatarRequest $request)
    {
        // Store the uploaded avatar in the 'avatars' directory on the public disk
        $path = $request->file('avatar')->store('avatars', 'public');

        // If the user already has an avatar, delete the old one
        if ($oldAvatar = $request->user()->avatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        // Update the user's avatar path in the database
        auth()->user()->update(['avatar' => $path]);

        // Redirect back with a success message
        return back()->with('message', 'Avatar updated');
    }
}
