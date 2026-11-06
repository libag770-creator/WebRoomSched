<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function __invoke(User $user)
    {
        $photoPath = str_replace('\\', '/', (string) $user->profile_photo_path);
        $photoPath = ltrim($photoPath, '/');

        if (str_starts_with($photoPath, 'storage/')) {
            $photoPath = substr($photoPath, strlen('storage/'));
        }

        if (
            !preg_match('/^profile-photos\/[A-Za-z0-9._-]+\.(?:jpe?g|png|webp)$/i', $photoPath)
            || !Storage::disk('public')->exists($photoPath)
        ) {
            abort(404);
        }

        return Storage::disk('public')->response($photoPath, null, [
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
