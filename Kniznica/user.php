<?php

include_once('db.php');

class User{

    public static function get_user_by_id($id){

    }

    public static function check_user($name, $pass, $check = 0){
        global $mysqli;

        $myusername = mysqli_real_escape_string($mysqli,$name);
        $mypassword = mysqli_real_escape_string($mysqli,$pass);
        $sql = "SELECT * FROM mbp_users WHERE username = '$myusername' and password = MD5('$mypassword')";
        $result = $mysqli->query($sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        // If result matched $myusername and $mypassword, table row must be 1 row

        if($count == 1) {
            if($check == 0){
                $_SESSION['login_user'] = $myusername;
                $_SESSION['id'] = $row['id'];
                $_SESSION['admin'] = $row['admin'];

                $result->free();
                header("location: index.php");
                return true;
            }else if ($check == 1) {
                return true;
            }
            else return true;

        }else {
            return false;
        }
    }

}
?>