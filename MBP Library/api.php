<?php

/**
 * @author Martin Hrebeňár
 */

// Allow from any origin
     if (isset($_SERVER['HTTP_ORIGIN'])) {
         // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
         // you want to allow, and if so:
         header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
         header('Access-Control-Allow-Credentials: true');
         header('Access-Control-Max-Age: 86400');    // cache for 1 day
     }

     // Access-Control headers are received during OPTIONS requests
     if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
             // may also be using PUT, PATCH, HEAD etc
             header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
             header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

         exit(0);
     }

include_once('db.php');
include_once('lecture.php');

global $mysqli;

if(isset($_POST['action']) && !empty($_POST['action'])){

    /**
     * select all languages that are being used by lectures in database
     * returns json encoded array
     */
    if(strcmp($_POST['action'], "get-avail-lang") === 0){

        $sql = "SELECT DISTINCT lang.id, lang.abbr, lang.name FROM mbp_lectures as l join mbp_languages as lang on lang.id = l.language_id";
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
