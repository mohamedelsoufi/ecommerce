<?php

namespace App\Http\Controllers;

use App\Service\myfatoorah;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function upload_image($image, $path, $width = 300, $height = 300){
        /*
            $image     image                                => required
            $path      path that i upload image in it       => required
            $width     image with                           => nullable
            $height    iamge height                         => nullable
        */

        //cange iamge name to random number
        try {
            $image_name = rand(0,1000000) . time() . '.' . $image->getClientOriginalExtension();
        
            $image_resize = Image::make($image->getRealPath());   
            $image_resize->resize($height, $width);
            $image_resize->save(public_path($path . '/' . $image_name));
            
            return $image_name;
            
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function percentage($number, $totalNumber){
        return ($number / 100) * $totalNumber;
    }
}
