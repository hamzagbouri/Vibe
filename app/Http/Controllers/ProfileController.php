<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date', 'before:yesterday'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'cover_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Add validation for profile picture
            'bio' => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->birthday = $request->birthday;
        $user->city = $request->city;
        $user->bio = $request->bio;

        // Handle profile picture upload
        if ($request->hasFile('cover_picture')) {
            // Delete old picture if it exists
            if ($user->cover_photo && file_exists(public_path( $user->cover_photo))) {
                unlink(public_path($user->cover_photo));
            }

            // Store the new picture
            $file = $request->file('cover_picture');
            $coverPhotoName = time() . '_' . $file->getClientOriginalName();
            $coverPhotoPath = 'uploads/cover_picture/' . $coverPhotoName;
            $file->move(public_path('uploads/cover_picture'), $coverPhotoName);
            $user->cover_photo = $coverPhotoPath; // Save the file name to the database
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
