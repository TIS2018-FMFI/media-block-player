<?php

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

function check_files($files, $trans_count){

    if($files['lecture_media']['size'] == 0) return false;

    if($files['lecture_script']['size'] == 0) return false;

    if($files['lecture_sync']['size'] == 0) return false;

    if ($trans_count == 0) return true;

    for($i = 1; $i <= $trans_count; $i++){
        if($files['lecture_trans_'.$i]['size'] == 0) return false;
    }

    return true;
}



?>