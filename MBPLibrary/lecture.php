<?php

/**
 * @author Martin Hrebeňár
 */

include_once('db.php');

class Lecture
{

    /**
     * @return int
     * returns count of all lectures in database
     */
    public static function get_lectures_count()
    {

        global $mysqli;

        $sql = "SELECT * FROM mbp_lectures WHERE active = 1";
        if (!$mysqli->connect_errno) {
            return mysqli_num_rows($mysqli->query($sql));
        }
        return -1;
    }

    /**
     * @return array|null
     * returns array of all languages from database
     */
    public static function get_languages()
    {
        global $mysqli;

        $res = array();

        $sql = "SELECT * FROM mbp_languages";
        if (!$mysqli->connect_errno) {
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
    public static function get_lectures($offset, $limit)
    {
        global $mysqli;

        $res = array();

        $sql = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id WHERE lec.active = 1 LIMIT $limit OFFSET $offset";
        if (!$mysqli->connect_errno) {
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, array('data' => $row, 'trans' => self::get_lecture_translations($row['id'])));
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
    public static function get_lecture_translations($lecture_id)
    {
        global $mysqli;

        $res = array();

        $sql = "SELECT t.id, t.name, t.trans_link, t.contributor_id, l.name as l_name, l.abbr FROM mbp_translations as t join mbp_languages as l on t.language_id = l.id where lecture_id = $lecture_id";
        if (!$mysqli->connect_errno) {
            $result = $mysqli->query($sql);

            if (mysqli_num_rows($result) == 0) return NULL;

            while ($row = $result->fetch_assoc()) {
                array_push($res, $row);
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
    public static function get_lectures_filtered($offset, $limit, $lang, $diff, $ord)
    {
        global $mysqli;

        $lang_sql = strcmp($lang, "Def") == 0 ? " " : " AND l.id = " . $lang;
        $diff_sql = strcmp($diff, "Def") == 0 ? " " : " AND lec.difficulty = " . $diff;

        $ord_sql = " ";

        switch ($ord) {
            case "1":
                $ord_sql = " ORDER BY lec.name ASC";
                break;
            case "2":
                $ord_sql = " ORDER BY lec.name DESC";
                break;
            case "3":
                $ord_sql = " ORDER BY lec.download_count ASC";
                break;
            case "4":
                $ord_sql = " ORDER BY lec.download_count DESC";
                break;
            case "5":
                $ord_sql = " ORDER BY lec.download_count ASC";
                break;
            case "6":
                $ord_sql = " ORDER BY lec.download_count DESC";
                break;
        }

        $res = array();

        $sql = "SELECT lec.*, l.name AS l_name, l.abbr FROM mbp_lectures AS lec JOIN mbp_languages AS l ON lec.language_id = l.id WHERE lec.active = 1" .
            $lang_sql .
            $diff_sql .
            $ord_sql .
            " LIMIT $limit OFFSET $offset";

        if (!$mysqli->connect_errno) {
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                array_push($res, array('data' => $row, 'trans' => self::get_lecture_translations($row['id'])));
            }
            return $res;
        }

        return NULL;
    }

    public static function get_lectures_filtered_count($lang, $diff, $ord)
    {
        global $mysqli;

        $lang_sql = strcmp($lang, "Def") == 0 ? " " : " AND l.id = " . $lang;
        $diff_sql = strcmp($diff, "Def") == 0 ? " " : " AND lec.difficulty = " . $diff;

        $ord_sql = " ";

        switch ($ord) {
            case "1":
                $ord_sql = " ORDER BY lec.name ASC";
                break;
            case "2":
                $ord_sql = " ORDER BY lec.name DESC";
                break;
            case "3":
                $ord_sql = " ORDER BY lec.download_count ASC";
                break;
            case "4":
                $ord_sql = " ORDER BY lec.download_count DESC";
                break;
            case "5":
                $ord_sql = " ORDER BY lec.download_count ASC";
                break;
            case "6":
                $ord_sql = " ORDER BY lec.download_count DESC";
                break;
        }

        $res = array();

        $sql = "SELECT lec.*, l.name AS l_name, l.abbr FROM mbp_lectures AS lec JOIN mbp_languages AS l ON lec.language_id = l.id WHERE lec.active = 1" .
            $lang_sql .
            $diff_sql .
            $ord_sql;

        if (!$mysqli->connect_errno) {
            return mysqli_num_rows($mysqli->query($sql));
        }
        return -1;
    }

    /**
     * @param array $data - passed $_POST variable
     * @param int $user_id - id of user who is saving lecture
     * @return int|mixed
     * creates new entry in database and returns the id of newly created lecture
     */
    public static function save_lecture($data, $user_id)
    {
        global $mysqli;

        $lec_tit = $data['lecture_title'];
        $lec_desc = $data['lecture_description'];
        $lec_diff = $data['lecture_diff'];
        $lec_lang = $data['lecture_lang'];

        $sql = "INSERT INTO mbp_lectures (name, description, difficulty, language_id, user_id, audio_link, text_link, sync_file_link)
                VALUES ('$lec_tit', '$lec_desc', '$lec_diff', '$lec_lang', '$user_id', '', '', '')";

        $new_id = 0;

        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
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
    public static function save_lecture_files($files, $lecture_name, $lec_id)
    {

        if (self::save_one_file($files['lecture_media'], "Media", $lecture_name)) {
            $media_f = 'Data/Media/' . $files['lecture_media']['name'];
            self::update_lecture_media_file($media_f, $lec_id);
        } else {
            $_SESION['msg'] = "Error while uploading files";
            $_SESION['msg_type'] = "ERR";
        };
        if (isset($files['lecture_script']) and self::save_one_file($files['lecture_script'], "Scripts", $lecture_name)) {
            $f = 'Data/Scripts/' . $files['lecture_script']['name'];
            self::update_lecture_script_file($f, $lec_id);
        } else {
            $_SESION['msg'] = "Error while uploading files";
            $_SESION['msg_type'] = "ERR";
        };
        if (isset($files['lecture_sync']) and self::save_one_file($files['lecture_sync'], "Syncs", $lecture_name)) {
            $f = 'Data/Syncs/' . $files['lecture_sync']['name'];
            self::update_lecture_sync_file($f, $lec_id);
        } else {
            $_SESION['msg'] = "Error while uploading files";
            $_SESION['msg_type'] = "ERR";
        };

        return true;
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
    private static function save_one_file($file, $module, $lecture_name, $salt = 0)
    {

        $target_dir = "Data/" . $module . "/";

        $new_file = $target_dir . $file['name'];

        if (!move_uploaded_file($file["tmp_name"], $new_file)) return false;
        return true;
    }

    /**
     * @param String $file - path of media file
     * @param int $lec_id - id of the lecture
     * @return bool
     * updates media file column for given lecture
     * returns false when update failed
     */
    private static function update_lecture_media_file($file, $lec_id)
    {
        global $mysqli;

        $sql = "UPDATE mbp_lectures SET audio_link='$file' WHERE id = '$lec_id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
                return true;
            }
        }
        return false;
    }

    private static function update_lecture_script_file($file, $lec_id)
    {
        global $mysqli;

        $sql = "UPDATE mbp_lectures SET text_link='$file', text_contributor_id='{$_SESSION['id']}' WHERE id = '$lec_id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
                return true;
            }
        }
        return false;
    }

    private static function update_lecture_sync_file($file, $lec_id)
    {
        global $mysqli;

        $sql = "UPDATE mbp_lectures SET sync_file_link='$file', sync_contributor_id='{$_SESSION['id']}' WHERE id = '$lec_id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $files - passed $_FILES variable
     * @param array $trans_data - passed $_POST variable
     * @param int $lec_id - id of the lecture
     * saves translation files provided when creating lecture and call function that saves it to database
     */
    public static function save_lecture_translations($files, $trans_data, $lec_id)
    {

        $lecture_name = $trans_data['lecture_title'];
        $trans_count = $trans_data['trans_count'];

        for ($i = 1; $i <= $trans_count; $i++) {
            if ($files['lecture_trans_' . $i]['size'] != 0) {
                self::save_one_file($files['lecture_trans_' . $i], 'Translations', $lecture_name, $i);
                $f_name = 'Data/Translations/' . $files['lecture_trans_' . $i]['name'];

                self::insert_translation($f_name, $lecture_name, $lec_id, $trans_data['trans_lang_' . $i]);
            }
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
    private static function insert_translation($file, $lecture_name, $lec_id, $lang_id)
    {
        global $mysqli;

        $trans_name = $lecture_name . '_' . $lang_id;
        $uid = $_SESSION['id'];

        $sql = "INSERT INTO mbp_translations (name, trans_link, lecture_id, language_id, contributor_id) VALUES ('$lecture_name','$file','$lec_id','$lang_id', '$uid')";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array|null
     * returns array of all distinct languages of lectures that are available in database
     */
    public static function get_avail_langs()
    {
        global $mysqli;

        $sql = "SELECT DISTINCT lang.id, lang.abbr, lang.name FROM mbp_lectures as l join mbp_languages as lang on lang.id = l.language_id WHERE l.active = 1";
        if (!$mysqli->connect_errno) {
            $res = array();
            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    array_push($res, $row);
                }
                return $res;
            }
        }
        return NULL;
    }


    public static function get_user_lectures($user_id)
    {
        global $mysqli;

        $sql = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id WHERE lec.user_id = $user_id AND lec.active = 1";
        if (!$mysqli->connect_errno) {
            $res = array();
            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    array_push($res, array('data' => $row, 'trans' => self::get_lecture_translations($row['id'])));
                }
                return $res;
            }
        }
        return NULL;
    }

    public static function get_user_favorite_lectures($user_id)
    {
        global $mysqli;

        $sql = "SELECT lecture_id FROM mbp_user_saved_lectures WHERE user_id = '$user_id'";
        if (!$mysqli->connect_errno) {
            $res = array();
            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $lid = $row['lecture_id'];
                    $sql2 = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id WHERE lec.id = $lid AND lec.active = 1";
                    if ($result2 = $mysqli->query($sql2)) {
                        while ($row2 = $result2->fetch_assoc()) {
                            array_push($res, array('data' => $row2, 'trans' => self::get_lecture_translations($row2['id'])));
                        }
                    }
                }
                return $res;
            }
        }

        return NULL;
    }

    public static function delete_lecture($lecture_id)
    {
        global $mysqli;

        /*$sql = "UPDATE mbp_lectures SET active = 0 WHERE id = '$lecture_id'";
        if (!$mysqli->connect_errno) {
            $mysqli->query($sql);
        }*/

        $sql_1 = "SELECT * FROM mbp_lectures WHERE id = $lecture_id";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql_1)) {
                while ($row = $result->fetch_assoc()) {
                    if (file_exists($row['audio_link'])) unlink($row['audio_link']);
                    if (file_exists($row['text_link'])) unlink($row['text_link']);
                    if (file_exists($row['sync_file_link'])) unlink($row['sync_file_link']);
                    $sql_1_1 = "DELETE FROM mbp_lectures WHERE id = $lecture_id";
                    $mysqli->query($sql_1_1);
                }
            }
        };

        $sql_2 = "SELECT * FROM mbp_translations WHERE lecture_id = $lecture_id";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql_2)) {
                while ($row = $result->fetch_assoc()) {
                    if (file_exists($row['trans_link'])) unlink($row['trans_link']);
                    $sql_2_1 = "DELETE FROM mbp_translations WHERE id = " . $row['id'];
                    $mysqli->query($sql_2_1);
                }
            }
        }

        return true;
    }

    public static function is_starred($lid, $uid)
    {
        global $mysqli;

        $sql = "SELECT * FROM mbp_user_saved_lectures WHERE lecture_id ='$lid' AND user_id = '$uid'";
        if (!$mysqli->connect_errno) {
            $result = $mysqli->query($sql);
            $count = mysqli_num_rows($result);
            if ($count > 0) {
                return true;
            }
            return false;
        }
        return false;
    }

    public static function update_lecture($data)
    {
        global $mysqli;

        $lec_tit = $data['lecture_title'];
        $lec_desc = $data['lecture_description'];
        $lec_diff = $data['lecture_diff'];
        $lec_lang = $data['lecture_lang'];
        $lid = $data['lid'];

        $sql = "UPDATE mbp_lectures SET name = '$lec_tit', description = '$lec_desc', difficulty = '$lec_diff', language_id = '$lec_lang' WHERE id = '$lid'";

        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
                return;
            }
        }

    }

    public static function update_lecture_files($files, $lid)
    {

        $lec = self::get_lecture_by_id($lid);

        if (isset($files['lecture_script']) and self::update_one_file($files['lecture_script'], "Scripts", $lec['text_link'])) {
            $f = 'Data/Scripts/' . $files['lecture_script']['name'];
            self::update_lecture_script_file($f, $lid);
        } else {
            $_SESION['msg'] = "Error while uploading files";
            $_SESION['msg_type'] = "ERR";
        };
        if (isset($files['lecture_sync']) and self::save_one_file($files['lecture_sync'], "Syncs", $lec['sync_file_link'])) {
            $f = 'Data/Syncs/' . $files['lecture_sync']['name'];
            self::update_lecture_sync_file($f, $lid);
        } else {
            $_SESION['msg'] = "Error while uploading files";
            $_SESION['msg_type'] = "ERR";
        };

        return true;
    }

    public static function get_lecture_by_id($lid)
    {
        global $mysqli;

        $sql = "SELECT lec.*, l.name as l_name, l.abbr FROM mbp_lectures as lec join mbp_languages as l on lec.language_id = l.id WHERE lec.id = '$lid' AND lec.active = 1";
        if (!$mysqli->connect_errno) {
            $res = array();
            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    array_push($res, array('data' => $row, 'trans' => self::get_lecture_translations($row['id'])));
                }
                return $res;
            }
        }
        return NULL;
    }

    public static function update_one_file($file, $module, $old_file)
    {
        $target_dir = "Data/" . $module . "/";

        $new_file = $target_dir . $file['name'];

        if (file_exists($old_file)) {
            unlink($old_file);
        }

        if (!move_uploaded_file($file["tmp_name"], $new_file)) return false;

        return true;
    }

    public static function delete_translation($id)
    {
        global $mysqli;

        $sql_2 = "SELECT * FROM mbp_translations WHERE id = $id";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql_2)) {
                while ($row = $result->fetch_assoc()) {
                    if (file_exists($row['trans_link'])) unlink($row['trans_link']);
                    $sql_2_1 = "DELETE FROM mbp_translations WHERE id = " . $row['id'];
                    $mysqli->query($sql_2_1);
                }
            }
        }

        return true;
    }

    public static function delete_file($lid, $type)
    {
        global $mysqli;

        $sql_1 = "SELECT * FROM mbp_lectures WHERE id = $lid";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql_1)) {
                while ($row = $result->fetch_assoc()) {
                    if ($type == 1) {
                        if (file_exists($row['text_link'])) unlink($row['text_link']);
                        $sql = "UPDATE mbp_lectures SET text_link='' WHERE id = '$lid'";
                        if (!$mysqli->connect_errno) {
                            if ($result = $mysqli->query($sql)) {
                                return true;
                            }
                        }
                    } elseif ($type == 2) {
                        if (file_exists($row['sync_file_link'])) unlink($row['sync_file_link']);
                        $sql = "UPDATE mbp_lectures SET sync_file_link='' WHERE id = '$lid'";
                        if (!$mysqli->connect_errno) {
                            if ($result = $mysqli->query($sql)) {
                                return true;
                            }
                        }
                    }
                }
            }
        };

        return false;
    }

}

?>
