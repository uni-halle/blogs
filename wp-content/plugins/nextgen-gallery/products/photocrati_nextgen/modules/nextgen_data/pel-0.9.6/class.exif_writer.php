<?php

// This file resides in the PEL directory so that it is not processed during the NextGen
// build process as the NGG package files cannot contain PHP 5.3+ code yet. See the
// C_Exif_Writer_Wrapper class which loads this file outside of the POPE module system.

/*
 * TAKE NOTE: when upgrading PEL check that the changes made to PelIfd.php in commit 7317 / 825b17c599b6
 * have been applied or adopted from upstream.
 */

require_once('autoload.php');

use lsolesen\pel\PelDataWindow;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTiff;
use lsolesen\pel\PelExif;
use lsolesen\pel\PelIfd;

class C_Exif_Writer
{
    static public function copy_metadata($old_file, $new_file)
    {
        $data = new PelDataWindow(@file_get_contents($old_file));
        $exif = new PelExif();

        if (PelJpeg::isValid($data))
        {
            $jpeg = $file = new PelJpeg();
            $jpeg->load($data);
            $exif = $jpeg->getExif();

            if ($exif === NULL)
            {
                $exif = new PelExif();
                $jpeg->setExif($exif);

                $tiff = new PelTiff();
                $exif->setTiff($tiff);
            }
            else {
                $tiff = $exif->getTiff();
            }

        }
        elseif (PelTiff::isValid($data)) {
            $tiff = $file = new PellTiff();
            $tiff->load($data);
        }
        else {
            return;
        }

        $ifd0 = $tiff->getIfd();
        if ($ifd0 === NULL)
        {
            $ifd0 = new PelIfd(PelIfd::IFD0);
            $tiff->setIfd($ifd0);
        }

        // Copy EXIF data to the new image and write it
        $new_image = new PelJpeg($new_file);
        $tiff->setIfd($ifd0);
        $exif->setTiff($tiff);
        $new_image->setExif($exif);
        $new_image->saveFile($new_file);

        // IF the original contained IPTC metadata we should copy it
        getimagesize($old_file, $iptc);
        if (isset($iptc['APP13']) && function_exists('iptcembed'))
        {
            $parsed = iptcparse($iptc['APP13']);
            $newiptc = '';
            foreach ($parsed as $key => $value) {
                $tag = str_replace("2#", '', $key);
                $newiptc .= self::build_iptc_tag($tag, $value[0]);
            }

            $metadata = iptcembed($newiptc, $new_file);
            $fp = fopen($new_file, 'wb');
            fwrite($fp, $metadata);
            fclose($fp);
        }
    }

    public static function build_iptc_tag($tag, $value)
    {
        $length = strlen($value);
        if ($length >= 0x8000)
        {
            return chr(0x1c)
                   . chr(2)
                   . chr($tag)
                   . chr(0x80)
                   . chr(0x04)
                   . chr(($length >> 24) & 0xff)
                   . chr(($length >> 16) & 0xff)
                   . chr(($length >> 8 ) & 0xff)
                   . chr(($length ) & 0xff)
                   . $value;
        }
        else {
            return chr(0x1c)
                   . chr(2)
                   . chr($tag)
                   . chr($length >> 8)
                   . chr($length & 0xff)
                   . $value;
        }
    }
}