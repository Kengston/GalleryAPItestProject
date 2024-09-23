<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the directory where images are stored
        $directory = storage_path('app/public/uploads');
        // Get all jpeg (and jpg) and png images from the directory
        $images = glob("{$directory}/*.{jpg,jpeg,png}", GLOB_BRACE);

        foreach ($images as $imagePath) {
            $image = basename($imagePath); // get the base name of the image path

            Image::create([
                'file_name' => $image,
                'file_path' => 'uploads/' . $image, // <-- adjust this to match your setup
            ]);
        }
    }
}
