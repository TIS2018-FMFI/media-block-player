<?php
/**
 * @author Martin Hrebeňár
 */

include_once('db.php');
include_once('lecture.php');

global $mysqli;

if(isset($_POST['action']) && !empty($_POST['action'])){

    if(strcmp($_POST['action'], 'increase_down_count') === 0){
        $id = $_POST['lec_id'];
        $sql = "UPDATE mbp_lectures SET download_count = download_count + 1 WHERE id = '$id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return json_encode(array('status' => 'OK'));
            }
        }
        return json_encode(array('status' => 'NotOK'));
    }

}


?>