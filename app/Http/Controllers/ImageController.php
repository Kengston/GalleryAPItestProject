<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image|max:5000',
        ]);

        $file = $request->file('image');
        $name = time().'_'.$file->getClientOriginalName();

        // Store the image in the `public` disk (may need to adjust based on your specific setup)
        $path = $file->storeAs('uploads', $name, 'public');

        // Save the path in the database
        $image = Image::create([
            'file_name' => $name,
            'file_path' => $path,
        ]);

        return response()->json($image, 201);
    }

    public function view($id)
    {
        $image = Image::findOrFail($id);
        $path = storage_path('app/public/'.$image->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        // Use Laravel's response helpers for direct file response.
        return response()->file($path);
    }
}
