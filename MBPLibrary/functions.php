<?php

/**
 * @author Martin Hrebeňár
 */


/**
 * @param string $file - name of file
 * @param int $w - desired width of final image
 * @param int $h - desired height of final image
 * @param bool $crop - crop or not
 * resize image to given dimensions and overwrite it
 * this function's author is : https://stackoverflow.com/a/14649689
 */
function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagejpeg($dst, $file, 100);

    return ;
}

/**
 * @param array $files - passed $_FILES variable
 * @param int $trans_count - number of translation files that was sent in form
 * @return bool
 * checks if all required files were really uploaded and there was no error
 * if file size == 0 it means it was not uploaded, or that it was fake file
 * if that happens function returns false
 */
function check_files($files, $trans_count){
    if($files['lecture_media']['size'] == 0 || $files['lecture_media']['size'] >= 36700160) return false;
    return true;
}



?>