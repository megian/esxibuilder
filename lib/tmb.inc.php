<?php

   /* 
     
    Created by: Matthew Harris 
       This script was created for anyone to use 
       so I don't really care who uses it or how 
       they use it. 

    Purpose: 
       The purpose of this script is that people 
       could rescale their JPEG or PNG images on 
       the fly instead of having to save the image 
       over and over again. 

    Things to know: 
     > There is no GIF support in this script right 
       now so dont bother trying :) 
     > Quality can only be adjusted with a JPEG. 
       The maximum is 100 so don't go above. 
     > The default quality used is 80. You can change 
       the default on line 32. Keep in mind that quality 
       changes only affect JPEG images. 
     > The scale is just like division. 2 will give you 
       half the size of the image. 

    */ 

    function thumbscale($source, $scale, $quality = 80) 
    { 
        /* Check for the image's exisitance */ 
        if (!file_exists($source)) { 
            echo 'File does not exist!'; 
        } 
        else { 
            $size = getimagesize($source); // Get the image dimensions and mime type 
            $w = $size[0] / $scale; // Width divided 
            $h = $size[1] / $scale; // Height divided 
            $resize = imagecreatetruecolor($w, $h); // Create a blank image 

            /* Check quality option. If quality is greater than 100, return error */ 
            if ($quality > 100) { 
                echo 'The maximum quality is 100. <br />Quality changes only affect JPEG images.'; 
            } 
            else {             
                header('Content-Type: '.$size['mime']); // Set the mime type for the image 

                switch ($size['mime']) { 
                    case 'image/jpeg': 
                    $im = imagecreatefromjpeg($source); 
                    imagecopyresampled($resize, $im, 0, 0, 0, 0, $w, $h, $size[0], $size[1]); // Resample the original JPEG 
                    imagejpeg($resize, '', $quality); // Output the new JPEG 
                    break; 

                    case 'image/png': 
                    $im = imagecreatefrompng($source); 
                    imagecopyresampled($resize, $im, 0, 0, 0, 0, $w, $h, $size[0], $size[1]); // Resample the original PNG 
                    imagepng($resize, '', $quality); // Output the new PNG 
                    break; 
                } 

                imagedestroy($im); 
            } 
        } 
    }
    
  function thumbjpg($source, $output, $w, $h, $quality=40) 
  {        
    if(!file_exists($source))
      echo 'File does not exist!'; 
    else
    { 
       $size = getimagesize($source);
       $resize = imagecreatetruecolor($w, $h); 
            
       if($quality > 100) $quality=100;
            
       switch ($size['mime'])
       {
         case 'image/jpeg':
           $im = imagecreatefromjpeg($source); 
           break;
         case 'image/png':
           $im = imagecreatefrompng($source); 
           break;
         case 'image/gif':
           $im = imagecreatefromgif($source); 
           break;
       }
            
       imagecopyresampled($resize, $im, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
       imagejpeg($resize, $output, $quality);
       imagedestroy($im); 
    } 
  } 
  
?>