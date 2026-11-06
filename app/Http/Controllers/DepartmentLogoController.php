<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Support\Facades\Storage;

class DepartmentLogoController extends Controller
{
    public function __invoke(Department $department)
    {
        $logoPath = str_replace('\\', '/', (string) $department->logo);

        if (
            !preg_match('/^departments\/[A-Za-z0-9._-]+\.(?:jpe?g|png|webp)$/i', $logoPath)
            || !Storage::disk('public')->exists($logoPath)
        ) {
            abort(404);
        }

        return Storage::disk('public')->response($logoPath, null, [
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
