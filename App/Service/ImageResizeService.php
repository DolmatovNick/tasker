<?php

namespace App\Service;

class ImageResizeService {

    private $imageFile;
    private $width;
    private $height;

    /**
     * Image type
     * @var string
     */
    private $mediaType;

    public function __construct($imageFile, $mediaType)
    {
        $this->imageFile = $imageFile;
        $this->mediaType = $mediaType;
    }

    public function changeImageSizeTo($width, $height)
    {
        $this->width = $width;
        $this->height = $height;

        if ( !$this->imageHasNormalSides() ) {
            $resource = $this->resizeImage();
            $this->saveResourceTo( $resource, $this->imageFile);
        }
    }

    private function imageHasNormalSides()
    {
        $image = getimagesize($this->imageFile);
        $width = $image[0];
        $height = $image[1];

        return $width == $this->width && $height == $this->height;
    }

    /**
     * Change image size
     * @return resource
     */
    private function resizeImage()
    {
        $file = $this->imageFile;
        $type = $this->mediaType;

        // Target dimensions
        $max_width = $this->width;   //320;
        $max_height = $this->height; //240;

        switch(strtolower($type))
        {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file);
                break;
            default:
                exit('Unsupported type: '.$file);
        }

        // Get current dimensions
        $old_width  = imagesx($image);
        $old_height = imagesy($image);

        // Calculate the scaling we need to do to fit the image inside our frame
        $scale      = min($max_width/$old_width, $max_height/$old_height);

        // Get the new dimensions
        $new_width  = ceil($scale*$old_width);
        $new_height = ceil($scale*$old_height);

        // Create new empty image
        $new = imagecreatetruecolor($new_width, $new_height);

        // Resize old image into new
        imagecopyresampled($new, $image,
            0, 0, 0, 0,
            $new_width, $new_height, $old_width, $old_height);

        return $new;
    }

    /**
     * Save image file
     * @param $resource
     * @param $file
     * @return bool
     */
    private function saveResourceTo($resource, $file) : bool
    {
        return imagepng($resource, $file);
    }


}