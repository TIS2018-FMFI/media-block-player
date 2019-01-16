<?php

/**
 * @author Martin Hrebeňár
 */

include_once ('db.php');

class Lecture{

    /**
     * @return int
     * returns count of all lectures in database
     */
    public static function get_lectures_count(){

        global $mysqli;

        $sql = "SELECT * FROM mbp_lectures WHERE active = 1";
        if(!$mysqli->connect_errno){
            return mysqli_num_rows($mysqli->query($sql));
        }
        return -1;
    }

    /**
     * @return array|null
     * returns array of all languages from database
     */
    public static function get_languages(){
        global $mysqli;

        $res = array();

        $sql = "SELECT * FROM mbp_languages";
        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, $row);
            }
            return $res;
        }

        return NULL;
    }

    /**
     * @param int $offset - offset by which lectures will be offset when selecting from database
     * @param int $limit - limit of how many lectures can be obtained from database
     * @return array|null
     * returns array of lectures which are limited and offset by parameters to be able to create pagination
     */
    public static function get_lectures($offset, $limit){
        global $mysqli;

        $res = array();

        $sql = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id WHERE lec.active = 1 LIMIT $limit OFFSET $offset";
        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, array('data'=>$row, 'trans'=> self::get_lecture_translations($row['id'])));
            }
            return $res;
        }

        return NULL;
    }

    /**
     * @param string $offset
     * @param string $limit
     * @param string $lang
     * @param string $diff
     * @param string $ord
     * @return array|null
     *  returns lectures matching the filter or null if no lectures were found
     */
    public static function get_lectures_filtered($offset, $limit, $lang, $diff, $ord){
        global $mysqli;

        $lang_sql = strcmp($lang, "Def") == 0 ? " " : " AND l.id = ".$lang;
        $diff_sql = strcmp($diff, "Def") == 0 ? " " : " AND lec.difficulty = ".$diff;

        $ord_sql = " ";

        switch ($ord){
            case "1": $ord_sql = " ORDER BY lec.name ASC"; break;
            case "2": $ord_sql = " ORDER BY lec.name DESC"; break;
            case "3": $ord_sql = " ORDER BY lec.download_count ASC"; break;
            case "4": $ord_sql = " ORDER BY lec.download_count DESC"; break;
            case "5": $ord_sql = " ORDER BY lec.download_count ASC"; break;
            case "6": $ord_sql = " ORDER BY lec.download_count DESC"; break;
        }

        $res = array();

        $sql = "SELECT lec.*, l.name AS l_name, l.abbr FROM mbp_lectures AS lec JOIN mbp_languages AS l ON lec.language_id = l.id WHERE lec.active = 1".
            $lang_sql.
            $diff_sql.
            $ord_sql.
            " LIMIT $limit OFFSET $offset";

        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, array('data'=>$row, 'trans'=> self::get_lecture_translations($row['id'])));
            }
            return $res;
        }

        return NULL;
    }

    /**
     * @param int $lecture_id - id of lecture
     * @return array
     * returns array of translations associated with lecture with given id
     */
    public static function get_lecture_translations($lecture_id){
        global $mysqli;

        $res = array();

        $sql = "SELECT t.id, t.name, t.trans_link, l.name as l_name, l.abbr FROM mbp_translations as t join mbp_languages as l on t.language_id = l.id where lecture_id = $lecture_id";
        if(!$mysqli->connect_errno){
            $result = $mysqli->query($sql);

            if (mysqli_num_rows($result) == 0) return NULL;

            while($row = $result->fetch_assoc()){
                array_push($res,$row);
            }
            return $res;
        }
        return NULL;
    }

    /**
     * @param array $data - passed $_POST variable
     * @param int $user_id - id of user who is saving lecture
     * @return int|mixed
     * creates new entry in database and returns the id of newly created lecture
     */
    public static function save_lecture($data, $user_id){
        global $mysqli;

        $lec_tit = $data['lecture_title'];
        $lec_desc = $data['lecture_description'];
        $lec_diff = $data['lecture_diff'];
        $lec_lang = $data['lecture_lang'];
        $lec_txt = 'Data/Scripts/'.$lec_tit.'.txt';
        $lec_syn = 'Data/Syncs/'.$lec_tit.'.mbpsf';

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

    /**
     * @param array $files - passed $_FILES variable
     * @param String $lecture_name - name of the lecture
     * @param int $lec_id - id of the lecture
     * @return bool
     * saves all files provided when creating lecture and creates associations of the files and lecture in database
     * returns true
     */
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

    /**
     * @param array $files - passed $_FILES variable
     * @param array $trans_data - passed $_POST variable
     * @param int $lec_id - id of the lecture
     * saves translation files provided when creating lecture and call function that saves it to database
     */
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

    /**
     * @param String $file - path of translation file
     * @param String $lecture_name - name of the lecture
     * @param int $lec_id - id of the lecture
     * @param int $lang_id - id of language of translation
     * @return bool
     * creates new entry for translation in database with association to given lecture and language
     * returns false when insertion failed
     */
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

    /**
     * @param String $file - path of media file
     * @param int $lec_id - id of the lecture
     * @return bool
     * updates media file column for given lecture
     * returns false when update failed
     */
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

    /**
     * @param Object $file - concrete file from $_FILES variable
     * @param String $module - name of module that file belongs to one from ['Media', 'Translations', 'Syncs', 'Scripts']
     * @param String $lecture_name - name of the lecture
     * @param int $salt - number to be added to a file name so that two files will not have same name
     * @return bool
     * saves uploaded file to correct place on server
     * return false if this procedure failed
     */
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

    /**
     * @return array|null
     * returns array of all distinct languages of lectures that are available in database
     */
    public static function get_avail_langs(){
        global $mysqli;

        $sql = "SELECT DISTINCT lang.id, lang.abbr, lang.name FROM mbp_lectures as l join mbp_languages as lang on lang.id = l.language_id WHERE l.active = 1";
        if (!$mysqli->connect_errno) {
            $res = array();
            if ($result = $mysqli->query($sql)){
                while($row = $result->fetch_assoc()){
                    array_push($res, $row);
                }
                return $res;
            }
        }
        return NULL;
    }


    public static function get_user_lectures($user_id){
        global $mysqli;

        $sql = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id WHERE lec.user_id = $user_id AND lec.active = 1";
        if (!$mysqli->connect_errno) {
            $res = array();
            if ($result = $mysqli->query($sql)){
                while($row = $result->fetch_assoc()){
                    array_push($res, array('data'=>$row, 'trans'=> self::get_lecture_translations($row['id'])));
                    //array_push($res, $row);
                }
                return $res;
            }
        }
        return NULL;
    }

    public static function delete_lecture($lecture_id){
        global $mysqli;

        $sql = "UPDATE mbp_lectures SET active = 0 WHERE id = '$lecture_id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return true;
            }
        }
        return NULL;
    }

}

?>