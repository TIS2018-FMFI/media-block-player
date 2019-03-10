<?php

/**
 * @author Martin Hrebeňár
 */

date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once('user.php');

Page::page_header('Admin Zone');
Page::page_navbar();

if ($_SESSION['admin'] != 1) {
    Page::error_card('You do not have permission to access this page.');
} else {
    if (isset($_GET['mode'])) {
        if ($_GET['mode'] == "contributions") {
            Page::admin_contributions();
        } else Page::warning_card("NYI");
    } else {
        Page::admin_page();
    }
}

?>


<?php
Page::page_footer();
?>

<!-- Place for custom and page related scripts -->
<script>
    $(document).ready(function () {
        $('.modal').modal();
        $('select').formSelect();
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


    $("[data-swal_id]").click(function () {
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
                swal("Error:" + textStatus + '\n' + errorThrown, {
                    icon: "error",
                });
            }
        });

    }
</script>

<?php
Page::page_foot();
?>
