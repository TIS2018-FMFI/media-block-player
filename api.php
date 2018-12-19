<?php

/**
 * @author Martin Hrebeňár
 */

include_once('db.php');
include_once('lecture.php');

global $mysqli;

if(isset($_POST['action']) && !empty($_POST['action'])){

    /**
     * select all languages that are being used by lectures in database
     * returns json encoded array
     */
    if(strcmp($_POST['action'], "get-avail-lang") === 0){

        $sql = "SELECT lang.id, lang.abbr, lang.name FROM mbp_lectures as l join mbp_languages as lang on lang.id = l.language_id";
        $res = array();
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
              while ($row = $result->fetch_assoc()) {
                array_push($res, array($row['id'], $row['name'], $row['abbr']));
              }
            }
        }

        echo json_encode(array('data'=>$res));
    }

    /**
     * get all lectures in selected languages
     * return json encoded array of objects
     */
    if(strcmp($_POST['action'], "get-lectures-in-lang") === 0){
        $lang = $_POST['primaryLang'];
        $sql = "SELECT lec.*, lang.abbr FROM mbp_lectures as lec JOIN mbp_languages as lang ON lec.language_id = lang.id WHERE abbr = '$lang'";
        $res = array();
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                while ($row = $result->fetch_assoc()) {
                    $t = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
                    $p = substr($t,0, strlen($t)-7);

                    $lec = new \stdClass();
                    $lec->id = $row['id'];
                    $lec->lecture_title = $row['name'];
                    $lec->description = $row['description'];
                    $lec->level = $row['difficulty'];
                    $lec->audio_file_link = $p.$row['audio_link'];
                    $lec->original_text_link = $p.$row['text_link'];
                    $lec->sync_file_link = $p.$row['sync_file_link'];

                    $tmp = Lecture::get_lecture_translations($row['id']);

                    $trans = new \stdClass();
                    foreach ($tmp as $item){
                        $path = $p.$item['trans_link'];
                        $lg = $item['abbr'];
                        $trans->$lg = $path;
                    }
                    $lec->translations = $trans;

                    array_push($res, $lec);
                }
            }
        }
        echo json_encode($res);
    }

}


?>
