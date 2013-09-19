<?php

/**
 * Image manipulation class
 *
 *    Copyright (C) 2005, Mike Tyson <mike@tzidesign.com>
 * 
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Library General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *
 * $Id$
 */

class Image
{
    var $resource;
    var $orig_path;
    var $metadata;
    
    function &Load($path)
    {
        if ( !function_exists("imagecreatefromjpeg") )
        {
           //DEBUG("GD not installed.");
           $ret = null;
           return $ret;
        }
        
        $image = new Image;
        $image->orig_path = $path;
        
        // Open image
        if( preg_match("/.jpe?g$/i", $path))
        {
            $image->resource = @imagecreatefromjpeg($path);
            if ( !$image->resource )
            {
                // Try to use imagemagick to convert to PNG, then open the PNG
                exec("convert '".mysql_escape_string($path)."' /tmp/'".mysql_escape_string(basename($path))."'.png");
                if ( file_exists("/tmp/".basename($path).".png") )
                {
                    $image->resource = @imagecreatefrompng("/tmp/".basename($path).".png");
                    @unlink("/tmp/".basename($path).".png");
                    if ( !$image->resource) return null;
                }
                else
                {
                    return null;
                }
            }
        }
        else if ( preg_match("/.gif$/i", $path))
        {
            $image->resource = imagecreatefromgif($path);
        }
        else if( preg_match("/.png$/i", $path))
        {
            $image->resource = imagecreatefrompng($path);
        }
        else
        {
            //DEBUG("Cannot open image $imagepath: File type not supported");
            return false;
        }
        
        if ( !$image->resource ) 
        {
            //DEBUG("Cannot open image $imagepath: Invalid file");
            return false;            
        }
        
        return $image;
    }


    function Release()
    {
        if ( $this->resource ) imagedestroy($this->resource);
    }
    
    function Width()
    {
        return imagesx($this->resource);
    }
    
    function Height()
    {
        return imagesy($this->resource);
    }
    
    function Scale($width, $height, $crop=false, $crop_width=-1, $crop_height=-1, $crop_x=-1, $crop_y=-1)
    {
        // Read original image dimensions
        $orig_w=imagesx($this->resource);
        $orig_h=imagesy($this->resource);

        if ( !$crop )
        {
           // If not cropping, then scale proportionately to within height/width
           if ($orig_w>$width || $orig_h>$height)
           {
              $img_w=$width;
              $img_h=$height;
              if ($img_w/$orig_w*$orig_h>$img_h)
                 $img_w=round($img_h*$orig_w/$orig_h);
              else
                 $img_h=round($img_w*$orig_h/$orig_w);
           } 
           else
           {
              $img_w=$orig_w;
              $img_h=$orig_h;
           }
           
           // Create scaled image
           $img=imagecreatetruecolor($img_w,$img_h);

           if ( !$img )
              return false;

           imagecopyresampled($img,$this->resource,
                           0,0,0,0,$img_w,$img_h,$orig_w,$orig_h);
        }
        else
        {
           $img = imagecreatetruecolor($width, $height);

           $crop_height = ( $crop_height != -1 ? $crop_height : $orig_h );
           $crop_width = ( $crop_width != -1 ? $crop_width : $orig_w );

           if ($crop_width/$width*$height>$crop_height)
              $crop_width=round($crop_height*$width/$height);
           else
              $crop_height=round($crop_width*$height/$width);

           $x = ( $crop_x != -1 ? $crop_x : round((($orig_w/2) - ($crop_width/2))));
           $y = ( $crop_y != -1 ? $crop_y : round((($orig_h/2) - ($crop_height/2))));

           imagecopyresampled($img, $this->resource, 0,0, $x, $y,
                 $width, $height, $crop_width, $crop_height);
        }
        
        $this->resource = $img;
    }
    
    function RoundCorners($bgcolor="FFFFFF", $radius=10)
    {
        imagealphablending($this->resource, false);
        $red = hexdec(substr($bgcolor, 0, 2));
        $green = hexdec(substr($bgcolor, 2, 2));
        $blue = hexdec(substr($bgcolor, 4, 2));
        $alpha = (strlen($bgcolor)>6 ? ((255/hexdec(substr($bgcolor, 4, 2)))*127) : false);
        
        $loops = array(
            array("startx" => 0,                                     "endx" => $radius,
                  "starty" => 0,                                     "endy" => $radius,
                  "centerx" => $radius,                              "centery" => $radius),  // Top left
                  
            array("startx" => imagesx($this->resource)-$radius,      "endx" => imagesx($this->resource),
                  "starty" => 0,                                     "endy" => $radius,
                  "centerx" => imagesx($this->resource)-1-$radius,   "centery" => $radius),  // Top right
                  
            array("startx" => 0,                                     "endx" => $radius,
                  "starty" => imagesy($this->resource)-1-$radius,    "endy" => imagesy($this->resource),
                   "centerx" => $radius,                             "centery" => imagesy($this->resource)-1-$radius),  // Bottom left
                  
            array("startx" => imagesx($this->resource)-1-$radius,    "endx" => imagesx($this->resource),
                  "starty" => imagesy($this->resource)-1-$radius,    "endy" => imagesy($this->resource),
                   "centerx" => imagesx($this->resource)-1-$radius,  "centery" => imagesy($this->resource)-1-$radius),  // Bottom right
                              
            );
        
        foreach ( $loops as $loop )
        for ( $i=$loop["startx"]; $i<$loop["endx"] && $i<imagesx($this->resource); $i++ )
        for ( $j=$loop["starty"]; $j<$loop["endy"] && $j<imagesy($this->resource); $j++ )
        {
            $distFromRadius = sqrt((($loop["centerx"]-$i)*($loop["centerx"]-$i)) + (($loop["centery"]-$j)*($loop["centery"]-$j)));
            $intensity = $distFromRadius - $radius;
            if ( $intensity < 0.0 ) continue;
            if ( $intensity > 1.0 ) $intensity = 1.0;

            $color = imagecolorat($this->resource, $i, $j);
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = ($color     ) & 0xFF;
            $a = ($color >> 24) & 0xFF;
            
            if ( $alpha === false || $alpha == 0 )
            {
                $color = imagecolorallocate($this->resource, 
                    ($intensity*$red)+((1.0-$intensity)*$r), 
                    ($intensity*$green)+((1.0-$intensity)*$g), 
                    ($intensity*$blue)+((1.0-$intensity)*$b));
            }
            else if ( $alpha == 127 )
            {
               $color = imagecolorallocatealpha($this->resource, 
                   $r, $g, $b,
                   ($intensity*$alpha)+((1.0-$intensity)*$a));               
            }
            else
            {
                $color = imagecolorallocatealpha($this->resource, 
                    ($intensity*$red)+((1.0-$intensity)*$r), 
                    ($intensity*$green)+((1.0-$intensity)*$g), 
                    ($intensity*$blue)+((1.0-$intensity)*$b),
                    ($intensity*$alpha)+((1.0-$intensity)*$a));
            }
                       
            imagesetpixel($this->resource, $i, $j, $color);
        }
    }
    
    function Save($path="")
    {
        if ( !$path ) $path = $orig_path;
        
        if( preg_match("/.jpe?g$/i", $path))
        {
            return imagejpeg($this->resource,$path,90);
        }
        else if ( preg_match("/.gif$/i", $path))
        {
            return imagegif($this->resource,$path);
        }
        else if( preg_match("/.png$/i", $path))
        {
            return imagesavealpha($this->resource, true);
            return imagepng($this->resource,$path);
        }
        else
        {
           //DEBUG("Cannot save image: File type not supported");
           return false;
        }
        
        return true;
    }
};


?>