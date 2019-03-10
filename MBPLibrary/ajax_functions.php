<?php
/**
 * @author Martin Hrebeňár
 */

include_once('db.php');
include_once('lecture.php');

global $mysqli;

header("Content-type: text/plain");

if (isset($_POST['action']) && !empty($_POST['action'])) {

    if (strcmp($_POST['action'], 'increase_down_count') === 0) {
        $id = $_POST['lec_id'];
        $sql = "UPDATE mbp_lectures SET download_count = download_count + 1 WHERE id = '$id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)) {
                echo json_encode(array('status' => 'OK'));
                exit();
            }
        }
        echo json_encode(array('status' => 'NotOK'));
        exit();
    }

    if (strcmp($_POST['action'], 'delete_lecture') === 0) {
        $id = $_POST['lec_id'];

        if (Lecture::delete_lecture($id)) {
            echo json_encode(array('status' => 'OK', "Hey" => "hey"));
            exit();
        };

    }

    if (strcmp($_POST['action'], 'star_lecture') === 0) {
        $lid = $_POST['lec_id'];
        $uid = $_POST['user_id'];

        $sql = "SELECT * FROM mbp_user_saved_lectures WHERE lecture_id ='$lid' AND user_id = '$uid'";
        if (!$mysqli->connect_errno) {
            $result = $mysqli->query($sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);
            if ($count > 0) {
                $delID = $row['id'];
                $sql2 = "DELETE FROM mbp_user_saved_lectures WHERE id ='$delID'";
                $mysqli->query($sql2);
                echo json_encode(array('status' => 'OK', 'mode' => 'DEL'));
                exit();
            }
            $sql3 = "INSERT INTO mbp_user_saved_lectures (user_id, lecture_id) VALUES ('$uid','$lid')";
            if ($result = $mysqli->query($sql3)) {
                echo json_encode(array('status' => 'OK', 'mode' => 'INS'));
                exit();
            }
        }

        echo json_encode(array('status' => 'NotOK-3'));
        exit();
    }

    if (strcmp($_POST['action'], 'delete_translation_file') === 0) {
        $trid = $_POST['trans_id'];
        Lecture::delete_translation($trid);

        echo json_encode(array('status' => 'OK'));
        exit();
    }

    if (strcmp($_POST['action'], 'delete_lecture_file') === 0) {
        $lid = $_POST['lec_id'];
        $type = $_POST['file_type'];

        if (Lecture::delete_file($lid, $type)){
            echo json_encode(array('status' => 'OK', 'type' => $type));
            exit();
        }

        echo json_encode(array('status' => 'NOK-3'));
        exit();
    }

    echo json_encode(array('status' => 'NotOK-2'));
    exit();
}

echo json_encode(array('status' => 'NotOK-1'));
exit();

?>