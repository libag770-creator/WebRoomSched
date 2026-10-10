<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
     * Upload or replace the authenticated user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = $request->user();
        $disk = Storage::disk('public');
        $newPath = null;

        try {
            $file = $request->file('profile_photo');

            if (!$file || !$file->isValid()) {
                return back()->with('error', 'Hindi valid ang napiling larawan. Pumili ulit ng JPG, PNG, o WebP na hanggang 2 MB.');
            }

            // Save to storage/app/public/profile-photos.
            $newPath = $file->store('profile-photos', 'public');

            if (!$newPath || !$disk->exists($newPath)) {
                return back()->with('error', 'Hindi na-save ang larawan sa storage. Pakisubukan ulit.');
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

            return back()->with('error', 'Hindi ma-update ang profile picture. Pakisuri ang storage at database, pagkatapos ay subukan ulit.');
        }

        return back()->with('success', 'Matagumpay na napalitan ang profile picture.');
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
