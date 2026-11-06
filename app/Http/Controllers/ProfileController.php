<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Update the username used by the username-and-password login form.
     */
    public function updateUsername(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('updateUsername', [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->getKey()),
            ],
            'current_password' => ['required', 'current_password'],
        ]);

        $user->username = $validated['username'];
        $user->save();

        return back()
            ->with('status', 'username-updated')
            ->with('account_profile_open', true);
    }

    /**
     * Upload or replace the authenticated user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validateWithBag('updatePhoto', [
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [], [
            'profile_photo' => 'profile photo',
        ]);

        $user = $request->user();
        $disk = Storage::disk('public');
        $newPath = null;

        try {
            $file = $request->file('profile_photo');

            if (!$file || !$file->isValid()) {
                return back()
                    ->with('error', 'The selected image is not valid. Choose a JPG, PNG, or WebP image up to 2 MB.')
                    ->with('account_profile_open', true);
            }

            // Save to storage/app/public/profile-photos.
            $newPath = $file->store('profile-photos', 'public');

            if (!$newPath || !$disk->exists($newPath)) {
                return back()
                    ->with('error', 'The photo could not be saved. Please try again.')
                    ->with('account_profile_open', true);
            }

            $oldPath = $user->profile_photo_path;

            // Store only the relative path in the database.
            $user->profile_photo_path = $newPath;
            $user->save();

            // Delete the previous file only after the database save succeeds.
            if ($oldPath && $oldPath !== $newPath) {
                $oldRelativePath = ltrim(str_replace('storage/', '', $oldPath), '/');

                if ($disk->exists($oldRelativePath)) {
                    $disk->delete($oldRelativePath);
                }
            }

            // Refresh the authenticated model so the updated path is available.
            $user->refresh();
        } catch (Throwable $e) {
            report($e);

            if ($newPath && $disk->exists($newPath)) {
                $disk->delete($newPath);
            }

            return back()
                ->with('error', 'The profile photo could not be updated. Please try again.')
                ->with('account_profile_open', true);
        }

        return back()
            ->with('status', 'photo-updated')
            ->with('account_profile_open', true);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
