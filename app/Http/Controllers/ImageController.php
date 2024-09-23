<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::all()->map(function ($image) {
            $image->file_path = Storage::url($image->file_path);
            return $image;
        });

        return response()->json($images);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image|max:5000',
        ]);

        $file = $request->file('image');
        $name = time().'_'.$file->getClientOriginalName();

        $path = $file->storeAs('uploads', $name, 'public');

        $image = Image::create([
            'file_name' => $name,
            'file_path' => $path,
        ]);

        $image->file_path = Storage::url($image->file_path);

        return response()->json($image, 201);
    }

    public function view($id)
    {
        $image = Image::findOrFail($id);
        $path = storage_path('app/public/'.$image->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
