<?php
date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once ('user.php');

Page::page_header('Home');
Page::page_navbar();

if(isset($_POST['save_lecture'])){
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";
}

?>

<main>

    <div class="jumbotron center-align" style="padding: 5em 0 3em 0;">
        <h1 style="margin:0">Media Block Player: Library</h1>
    </div>

    <div class='container' style="margin-bottom: 2em">
        <div class='center-align'>
            <h5>This is a library of lectures compatible with our online training application that you can find: HERE</h5>
        </div>
    </div>

    <?php if(isset($_SESSION['id'])){?>
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light purple lighten-1" href="lecture_add.php""><i class="material-icons">add</i></a>
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
    if(jQuery){console.log("JQ OK");}else console.log("JQ NotOK");


    // Or with jQuery

    $(document).ready(function(){
        $('.modal').modal();
    });


    $("[data-lecture]").click(function () {
        console.log(this.getAttribute('data-lecture'));
        $form_id = "#download_"+this.getAttribute('data-lecture');
        $form = $($form_id);
        var lec_name = this.getAttribute('data-lecture-name');


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
            return new Promise(function(resolve, reject) {
                JSZipUtils.getBinaryContent(url, function (err, data) {
                    if(err) {
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
                zip.file(filename, urlToPromise(url), {binary:true});
            });

            // when everything has been downloaded, we can trigger the dl
            zip.generateAsync({type:"blob"}, function updateCallback(metadata) {
                var msg = "progression : " + metadata.percent.toFixed(2) + " %";
                if(metadata.currentFile) {
                    msg += ", current file = " + metadata.currentFile;
                }
                showMessage(msg);
                updatePercent(metadata.percent|0);
            })
                .then(function callback(blob) {
                    console.log("here we go");
                    // see FileSaver.js
                    saveAs(blob, lec_name+".zip");

                    showMessage("done !");
                }, function (e) {
                    showError(e);
                });

            return false;
        });
    })
</script>

<?php
Page::page_foot();
?>