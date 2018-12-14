<?php

include_once('db.php');

class User{

    public static function get_user_by_id($id){
        global $mysqli;

        $sql = "SELECT id, username, admin, email, image FROM mbp_users WHERE id = '$id' LIMIT 1";
        $result = $mysqli->query($sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if($count == 0){
            return NULL;
        }

        return $row;
    }

    public static function get_user_profile($user_id){
        global $mysqli;

        $sql = "SELECT u.id, username, admin, email, image, first_name, last_name, gender, age, native_lang_id, l.name, l.abbr 
                FROM mbp_users as u JOIN mbp_languages as l on u.native_lang_id = l.id
                WHERE u.id = '$user_id'";
        $result = $mysqli->query($sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if($count == 0){
            return NULL;
        }

        return $row;
    }

    public static function check_user($name, $pass, $check = 0){
        global $mysqli;

        $myusername = mysqli_real_escape_string($mysqli,$name);
        $mypassword = mysqli_real_escape_string($mysqli,$pass);
        $sql = "SELECT * FROM mbp_users WHERE (username = '$myusername' or email = '$myusername') and password = MD5('$mypassword')";
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

    public static function add_user($username, $password, $email){
        global $mysqli;

        $sql = "INSERT INTO mbp_users (username, password, email) VALUES ('$username', MD5('$password'), '$email')";

        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return true;
            }
        }
        return false;

    }

    public static function edit_profile($data, $id){
        global $mysqli;

        $sql = "UPDATE mbp_users SET email='".addslashes(htmlspecialchars(strip_tags($data['email']))).
                "', first_name='".addslashes(htmlspecialchars(strip_tags($data['first_name']))).
                "', last_name='".addslashes(htmlspecialchars(strip_tags($data['last_name']))).
                "', gender='".addslashes(htmlspecialchars(strip_tags(isset($data['gender']) ? $data['gender'] : NULL))).
                "', age='".addslashes(htmlspecialchars(strip_tags($data['age']))).
                "', native_lang_id='".addslashes(htmlspecialchars(strip_tags($data['native_lang']))).
                "' WHERE id = '$id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return true;
            }
        }
        return false;
    }

    public static function edit_profile_picture($id, $image_type){
        global $mysqli;

        $sql = "UPDATE mbp_users SET image='$id.$image_type' WHERE id = '$id'";
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
                return true;
            }
        }
        return false;
    }

}
?>