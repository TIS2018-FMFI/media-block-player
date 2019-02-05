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
            if (!file_exists('Pictures/Profiles/')) {
                mkdir('Pictures/Profiles/', 0777, true);
            }
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
                    $_SESSION['msg_status'] = 'ERR';
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
                        $_SESSION['msg_status'] = 'ERR';
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
            $('.modal').modal();
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
        });

        $("[data-lecture]").click(function () {
            $data = this.getAttribute('data-lecture');
            $lec_id = this.getAttribute('data-lecture-id');
            $form_id = "#download_" + $data;
            $form = $($form_id);
            var lec_name = this.getAttribute('data-lecture-name');

            $("#progress_bar_" + $data).show();

            var Promise = window.Promise;
            if (!Promise) {
                Promise = JSZip.external.Promise;
            }

            /**
             * Fetch the content and return the associated promise.
             * @param {String} url the url of the content to fetch.
             * @return {Promise} the promise containing the data.
             */
            function urlToPromise(url) {
                return new Promise(function (resolve, reject) {
                    JSZipUtils.getBinaryContent(url, function (err, data) {
                        if (err) {
                            reject(err);
                        } else {
                            resolve(data);
                        }
                    });
                });
            }

            $form.on("submit", function () {
                resetMessage();

                var zip = new JSZip();

                // find every checked item
                $(this).find(":checked").each(function () {
                    var $this = $(this);
                    var url = $this.data("url");
                    var filename = url.replace(/.*\//g, "");
                    zip.file(filename, urlToPromise(url), {binary: true});
                });

                // when everything has been downloaded, we can trigger the dl
                zip.generateAsync({type: "blob"}, function updateCallback(metadata) {
                    var msg = "progression : " + metadata.percent.toFixed(2) + " %";
                    if (metadata.currentFile) {
                        msg += ", current file = " + metadata.currentFile;
                    }
                    showMessage(msg);
                    updatePercent(metadata.percent | 0);

                })
                    .then(function callback(blob) {
                        saveAs(blob, lec_name + ".zip");
                        $("#progress_bar_" + $data).hide();
                        showMessage("done !");
                    }, function (e) {
                        showError(e);
                    });

                return false;
            });
        });


        $("[data-swal_id]").click(function() {
            var lid = this.getAttribute('data-swal_lec_id');
            console.log("klik" + lid);
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this article!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        delete_lecture(lid);
                    }
                });
        });

        function delete_lecture($lec_id) {
            $.ajax({
                type: 'POST',
                url: './ajax_functions.php',
                data: {
                    action: "delete_lecture",
                    lec_id: $lec_id,
                },
                dataType: 'json',
                success: function (data) {
                    swal("Poof! Your article has been deleted!", {
                        icon: "success",
                    });
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal("Error:"+textStatus+'\n'+errorThrown, {
                        icon: "error",
                    });
                }
            });

        }
    </script>

<?php
Page::page_foot();
?>
