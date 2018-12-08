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

    public static function get_lecture_translations($lecture_id){
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

}

?>