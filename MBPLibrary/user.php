<?php

/**
 * @author Martin Hrebeňár
 */

include_once('db.php');

class User{

    /**
     * @param int $id - ide of the user
     * @return array|null
     * tries to find user in database by given id
     * if fail returns NULL, otherwise return array
     */
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

    /**
     * @param int $user_id - id of the user
     * @return array|null
     * tries to get all data associated user with given id
     * returns NULL when not found, otherwise array
     */
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

    /**
     * @param string $name - username
     * @param string $pass - password
     * @param int $check
     * @return bool
     * checks in database for given user login credentials, there must be exactly one result for function to return true
     */
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
                $_SESSION['msg'] = "Welcome ". $myusername;
                $_SESSION['msg_status'] = 'OK';
                exit(header("location: index.php"));
            }else if ($check == 1) {
                return true;
            }
            else return true;

        }else {
            return false;
        }
    }

    /**
     * @param string $username - user's username
     * @param string $password - user's password
     * @param string $email - user's email address
     * @return bool
     * creates new entry for user in database
     * returns false when inserting failed
     */
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

    /**
     * @param array $data - passed $_POST variable
     * @param int $id - id of the user
     * @return bool
     * updates entry for given user with data posted in edit form
     * returns false when inserting failed
     */
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

    /**
     * @param int $id - id of the user
     * @param string $image_type - file extension of picture
     * @return bool
     * updates image column for user entry in database
     * returns false when inserting failed
     */
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