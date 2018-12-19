<?php

/**
 * @author Martin Hrebeňár
 */

date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once ('user.php');
include_once ('functions.php');

Page::page_header('Profile');
Page::page_navbar();

if (isset($_GET['id'])){
    if (isset($_POST['save_profile'])){
        User::edit_profile($_POST, $_GET['id']);
        if($_FILES['picture'] && $_FILES['picture']['size'] > 0) {
            //var_dump($_FILES);
            $target_dir = "Pictures/Profiles/";
            $target_file = $target_dir . basename($_FILES["picture"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $new_file = $target_dir . $_GET['id'] . "." . $imageFileType;

            if (file_exists($target_file)) {
                unlink($target_file);
            }

            if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
                if(!isset($_SESSION['msg'])){
                    $_SESSION['msg'] = "Sorry, only JPG, JPEG files are allowed.";
                    $_SESSION['msg_status'] = 'WAR';
                }
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                if(!isset($_SESSION['msg'])){
                    $_SESSION['msg'] = "Sorry, your file was not uploaded.";
                    $_SESSION['msg_staus'] = 'ERR';
                }
            } else {
                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $new_file)) {
                    User::edit_profile_picture($_GET['id'], $imageFileType);
                    chmod($new_file, 0777);
                    resize_image($new_file, 500, 500, true);
                    unset($_FILES);
                } else {
                    if(!isset($_SESSION['msg'])){
                        $_SESSION['msg'] = "Sorry, there was an error uploading your file.";
                        $_SESSION['msg_staus'] = 'ERR';
                    }
                }
            }
        }
        unset($_POST);

        if(!isset($_SESSION['msg'])){
            $_SESSION['msg'] = "Your profile was updated.";
            $_SESSION['msg_status'] = 'OK';
        }

        header("Refresh:0; location: profile.php?id=".$_GET['id']."");
    }

    if(isset($_GET['edit'])){
        if($_GET['id'] == $_SESSION['id'] || $_SESSION['admin'] == 1){
            Page::profile_edit($_GET['id']);
        }
        else Page::error_card('You do not have permission to access this page.');
    }
    else {
        Page::profile_detail($_GET['id']);
    }


} else Page::error_card("You do not have permission to access this page.");


Page::page_footer();
?>

    <!-- Place for custom and page related scripts -->
    <script>
        $(document).ready(function(){
            $('select').formSelect();
        });

        $('#profile_edit').validate({
            email:{
                required: true,
                email: true,
            },
            messages:{
                email:{
                    required: 'Please enter your email.',
                    email: 'Entered email is not valid',
                },
            },
            errorElement : 'div',
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                    console.log(error);
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        })

    </script>

<?php
Page::page_foot();
?>