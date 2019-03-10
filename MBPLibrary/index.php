<?php

/**
 * @author Martin Hrebeňár
 */

date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once('user.php');

Page::page_header('Home');
Page::page_navbar();

?>

<main style="margin: 3em 0">
    <?php
    if (isset($_SESSION['msg'])) {
        Page::page_message($_SESSION['msg_status'], $_SESSION['msg']);
        unset($_SESSION['msg']);
        unset($_SESSION['msg_status']);
    }
    ?>

    <?php if (isset($_SESSION['id'])) { ?>
        <div class="fixed-action-btn">
            <a class="btn-floating pulse btn-large waves-effect waves-light purple lighten-1" href="lecture_add.php""><i
                    class="material-icons">add</i></a>
        </div>
        <?php
    }

    Page::list_lectures();
    ?>

</main>

<?php
Page::page_footer();
?>

<!-- Place for custom and page related scripts -->
<script>

    $(document).ready(function () {
        $('.modal').modal();
        $('select').formSelect();

        $('.star_btn').click(function () {
            let lid = $(this).data('lectureid');
            let uid = $(this).data('userid');
            console.log(lid);

            star_lecture(lid, uid);
        })
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
                    plus_download_count($lec_id);
                    saveAs(blob, lec_name + ".zip");
                    $("#progress_bar_" + $data).hide();
                    showMessage("done !");
                }, function (e) {
                    showError(e);
                });

            return false;
        });
    });

    function plus_download_count($lec_id) {
        $.ajax({
            type: 'POST',
            url: "./ajax_functions.php",
            data: {
                action: "increase_down_count",
                lec_id: $lec_id,
            },
            dataType: 'json',
            success: function (data) {
                //console.log(data);
            }
        })
    }

    function star_lecture($lec_id, $user_id) {
        console.log($lec_id+" - "+$user_id);
        $.ajax({
            type: 'POST',
            url: "./ajax_functions.php",
            data:{
                action: "star_lecture",
                lec_id: $lec_id,
                user_id: $user_id,
            },
            dataType: 'json',
            success: function (data) {
                if(data.mode == "INS"){
                    $('#star_icon_'+$lec_id).html("star");
                }
                else if(data.mode == "DEL"){
                    $('#star_icon_'+$lec_id).html("star_border");
                }
            }
        })
    }
</script>

<?php
Page::page_foot();
?>
