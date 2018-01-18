<?php

namespace App\Handlers;

use Intervention\Image\ImageManagerStatic as Image;

class ImagesHandler
{
    protected $allowed_ext = ["png", "jpg", 'jpeg'];

    public function __construct()
    {
        Image::configure(array(
            'driver'=>'gd',
        ));
    }

    private function check_path_exists($path)
    {
        if(!is_dir($path) && !@mkdir($path)){
            $dirArr = explode('/', $path);
            array_pop($dirArr);
            $newPath = implode('/', $dirArr);
            $this->check_path_exists($newPath);
            @mkdir($path);
        }
    }

    public function save($file, $folder, $file_prefix, $file_type = "base64")
    {
        //folder of saved
        $folder_name = "images/uploads/$folder/" . date("Ym", time()) . "/" . date("d", time());

        //whole path of saved
        $upload_path = public_path() . "/" . $folder_name;

        //get img ext
        if($file_type == "base64"){
            preg_match("/^(data:\s*image\/(\w+);base64,)/", $file, $result);
            $extension = $result[2];
        } else {
            $img = Image::make($file);
            $mime = $img->mime();
            $ext = explode('/', $mime);
            $extension = $ext[1]?:'png';
        }

        //file name of saved
        $file_name = $file_prefix . "_" . time() . "-" . str_random(10) . "." . $extension;

        if(! in_array($extension, $this->allowed_ext)){
            return false;
        }

        //check path
        $this->check_path_exists($upload_path);

        if($file_type == "base64") {
            if(file_put_contents($upload_path."/".$file_name, base64_decode(str_replace($result[1], '', $file)))){
                return $folder_name . "/" . $file_name;
            }else{
                return false;
            }
        } else {
            $img->save($upload_path."/".$file_name);

            return $folder_name . "/" . $file_name;
        }

    }
}
