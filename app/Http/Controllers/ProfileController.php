<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // @dec     Update profile info
    // @route   PUT /profile
    public function update(Request $request) : RedirectResponse
    {
        // Get logged in user
        $user = Auth::user();

        $validatedData = $request->validate([
           'name' => 'required|string',
           'email' => 'required|string|email',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Check if their was a file upload
        if ($request->hasFile('avatar')) {
            if($user->avatar){
                // Delete old logo
                Storage::delete('public/' . $user->avatar);
            }

            // Save the avatar and save path into db
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile info updated!');
    }
}
