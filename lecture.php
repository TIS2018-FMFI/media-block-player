<?php

include_once ('db.php');

class Lecture{

    public static function get_lectures_count(){

        global $mysqli;

        $sql = "SELECT * FROM mbp_lectures";
        if(!$mysqli->connect_errno){
            return mysqli_num_rows($mysqli->query($sql));
        }
        return -1;
    }

    public static function get_languages(){
        global $mysqli;

        $res = array();

        $sql = "SELECT * FROM mbp_languages";
        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, $row);
            }
        }

        return $res;
    }

    public static function get_lectures($offset, $limit){
        global $mysqli;

        $res = array();

        $sql = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id LIMIT $limit OFFSET $offset";
        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, array('data'=>$row, 'trans'=> self::get_lecture_translations($row['id'])));
            }
        }

        return $res;
    }

    private static function get_lecture_translations($lecture_id){
        global $mysqli;

        $res = array();

        $sql = "SELECT t.id, t.name, t.trans_link, l.name as l_name, l.abbr FROM mbp_translations as t join mbp_languages as l on t.language_id = l.id where lecture_id = $lecture_id";
        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);
            while($row = $result->fetch_assoc()){
                array_push($res,$row);
            }
        }
        //var_dump($res);
        return $res;
    }

    public static function save_lecture($data, $user_id){
        global $mysqli;

        $lec_tit = $data['lecture_title'];
        $lec_desc = $data['lecture_description'];
        $lec_diff = $data['lecture_diff'];
        $lec_lang = $data['lecture_lang'];
        $lec_txt = 'Data/Scripts/'.$lec_tit.'.txt';
        $lec_syn = 'Data/Syncs/'.$lec_tit.'.txt';

        $sql = "INSERT INTO mbp_lectures (name, description, difficulty, language_id, user_id, audio_link, text_link, sync_file_link)
                VALUES ('$lec_tit', '$lec_desc', '$lec_diff', '$lec_lang', '$user_id', ' ', '$lec_txt', '$lec_syn')";

        $new_id = 0;

        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                $new_id = $mysqli->insert_id;
            }
        }

        return $new_id;
    }

    public static function save_lecture_files($files, $lecture_name, $lec_id){

        if(self::save_one_file($files['lecture_media'], "Media", $lecture_name)){
            $fileType = strtolower(pathinfo($files['lecture_media']['name'],PATHINFO_EXTENSION));
            $media_f = 'Data/Media/'.$lecture_name.'.'.$fileType;
            self::update_lecture_media_file($media_f, $lec_id);
        };
        self::save_one_file($files['lecture_script'], "Scripts", $lecture_name);
        self::save_one_file($files['lecture_sync'], "Syncs", $lecture_name);

        return true;
    }

    public static function save_lecture_translations($files, $trans_data, $lec_id){

        $lecture_name = $trans_data['lecture_title'];
        $trans_count = $trans_data['trans_count'];

        for($i = 1; $i <= $trans_count; $i++){
            self::save_one_file($files['lecture_trans_'.$i], 'Translations', $lecture_name, $i);
            $fileType = strtolower(pathinfo($files['lecture_trans_'.$i]['name'],PATHINFO_EXTENSION));
            $f_name = 'Data/Translations/'.$lecture_name.$i.'.'.$fileType;

            self::insert_translation($f_name, $lecture_name, $lec_id, $trans_data['trans_lang_'.$i]);
        }

    }

    private static function insert_translation($file, $lecture_name, $lec_id, $lang_id){
        global $mysqli;

        $trans_name = $lecture_name.'_'.$lang_id;

        $sql = "INSERT INTO mbp_translations (name, trans_link, lecture_id, language_id) VALUES ('$lecture_name','$file','$lec_id','$lang_id')";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return true;
            }
        }
        return false;
    }

    private static function update_lecture_media_file($file, $lec_id){
        global $mysqli;

        $sql = "UPDATE mbp_lectures SET audio_link='$file' WHERE id = '$lec_id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return true;
            }
        }
        return false;
    }

    private static function save_one_file($file, $module, $lecture_name, $salt = 0){

        $target_dir = "Data/".$module."/";
        $up_file = $target_dir . $file['name'];
        $fileType = strtolower(pathinfo($up_file,PATHINFO_EXTENSION));
        if (strcmp($module, 'Translations') == 0){
            $new_file = $target_dir . $lecture_name .$salt.".". $fileType;
        }
        else $new_file = $target_dir . $lecture_name .".". $fileType;

        if(!move_uploaded_file($file["tmp_name"], $new_file)) return false;
        return true;
    }

}

?>