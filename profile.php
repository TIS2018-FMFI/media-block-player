<?php
date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once ('user.php');
include_once ('functions.php');

Page::page_header('Profile');
Page::page_navbar();

if (isset($_GET['id'])){
    if(isset($_GET['edit'])){
        Page::profile_edit($_GET['id']);
    }
    else {
        Page::profile_detail($_GET['id']);
    }

    if (isset($_POST['save_profile'])){
        User::edit_profile($_POST, $_GET['id']);

        $target_dir = "Pictures/Profiles/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $new_file = $target_dir . $_GET['id'] .".". $imageFileType;

// Check if file already exists
        if (file_exists($target_file)) {
            echo "File already exists.";
            unlink($target_file);
        }
// Check file size
        /*if ($_FILES["picture"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }*/
// Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $new_file)) {
                echo "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
                User::edit_profile_picture($_GET['id'], $imageFileType);
                chmod ($new_file, 0777);
                resize_image($_FILES["picture"]["tmp_name"],500,500, true);
                unset($_POST);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        header("location: profile.php?id=".$_GET['id']."");
    }

} else echo "<p class='permission_error'>Neplatný vstup pre stránku alebo nemáš oprávnenie na prezeranie jej obsahu.</p>";


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