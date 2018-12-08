<?php

include_once ('lecture.php');
include_once ('user.php');

class Page{

    public static function page_header($title){
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">

            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>-->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
            <link rel="stylesheet" type="text/css" href="css/materialize.css">
            <script src="js/materialize.js"></script>
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

            <style>
                body {
                    display: flex;
                    min-height: 100vh;
                    flex-direction: column;
                }

                main {
                    flex: 1 0 auto;
                }
            </style>

            <link rel="stylesheet" href="css/color_scheme.css">
            <link rel="stylesheet" href="css/style.css">

            <title><?php echo $title; ?></title>
        </head>
        <?php
    }

public static function page_navbar(){
    ?>
<body>
    <header>
        <nav>
            <div class="nav-wrapper blue" style="padding: 0 2em;">
                <a href="index.php" class="brand-logo">MBP</a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">Menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <?php if (isset($_SESSION['id'])){ ?>
                        <li class="waves-effect">
                            <a class="waves-effect" href="logout.php">Logout</a>
                        </li>
                    <?php }else{ ?>

                        <li class="waves-effect">
                            <a class="waves-effect" href="login.php">Login</a>
                        </li>
                        <li class="waves-effect">
                            <a class="waves-effect" href="register.php">Register</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>

        <ul class="sidenav" id="mobile-demo">
            <?php if (isset($_SESSION['id'])){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php }else{ ?>

                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php } ?>
        </ul>
    </header>
    <?php
}

    public static function page_footer(){
        ?>
        <footer class='page-footer blue'>
            <div class="footer-copyright">
                <div class="container">
                    &copy; Created by 'Prvá skupina v zozname' as a school project, 2018
                </div>
            </div>
        </footer>

        <script src='js/jszip.min.js'></script>
        <script src='js/jszip-utils.min.js'></script>
        <script src='js/helpers.js'></script>
        <script src='js/FileSaver.js'></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var elems = document.querySelectorAll('.modal');
                var instances = M.Modal.init(elems, options);
            });

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

        </body>
        </html>
        <?php
    }

    public static function list_lectures(){
        $page_entries = 7;
        $start_from = 0;
        if (isset($_GET['page'])) $start_from = (($_GET['page']-1) * $page_entries)+2;

        echo "<div class='container'>";
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Lecture name</th>";
        echo "<th>Lecture Language</th>";
        echo "<th>Difficulty</th>";
        echo "<th>Added</th>";
        echo "<th>Updated</th>";
        echo "<th></th>";
        echo "</tr></thead>";

        $lectures_count = Lecture::get_lectures_count();

        $lectures = Lecture::get_lectures($start_from, $page_entries);
        echo "<tbody>";
        foreach ($lectures as $lecture){

            $modalname = hash('md5', $lecture['data']['id']+$lecture['data']['name']);
            $lecture_name = $lecture['data']['name'];
            $lecture_id = $lecture['data']['id'];
            $lecture_lang = $lecture['data']['l_name'];
            $lecture_diff = $lecture['data']['difficulty'];


            echo "<tr>";
            echo "<td>$lecture_id</td>";
            echo "<td>$lecture_name</td>";
            echo "<td>$lecture_lang</td>";
            echo "<td>$lecture_diff</td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td><a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'>$lecture_name</a></td>";
            echo "</tr>";

        }


        echo "</tbody></table><ul class='pagination'>";
        echo "<li class='disabled'><a href='#!'><i class='material-icons'>chevron_left</i></a></li>";
        for ($i = 1; $i <= (count(scandir("Data/A-V sources")) / $page_entries)+1; $i++){
            if ((isset($_GET['page']) && $i == $_GET['page']) || $i == 1) echo "<li class='active blue'><a href='?page=$i'>$i</a></li>";
            else echo "<li class='waves-effect'><a href='?page=$i'>$i</a></li>";
        }
        echo "<li class='waves-effect'><a href='#!'><i class='material-icons'>chevron_right</i></a></li>";
        echo "</ul>";
        echo "</div>";

        foreach ($lectures as $lecture) {
            $modalname = hash('md5', $lecture['data']['id']+$lecture['data']['name']);
            $lecture_desc = $lecture['data']['description'];
            $lecture_media_link = $lecture['data']['audio_link'];
            $lecture_text_link = $lecture['data']['text_link'];
            $lecture_sync_link = $lecture['data']['sync_file_link'];
            $lecture_lang = $lecture['data']['l_name'];
            $lecture_name = $lecture['data']['name'];


            /*print "<pre>";
            print_r($lecture);
            print "</pre>";*/
            ?>
            <div class='modal' id='<?php echo $modalname ?>'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4><?php echo $lecture_name ?></h4>
                        <a href='#' class='modal-close' style='position: fixed; right: 1em; top: 0.5em; font-size: 2em;'>&times;</a></div>


                    <p><strong>Language:</strong> <?php echo $lecture_lang ?></p>
                    <p><strong>Contributor:</strong> ...</p>
                    <p><strong>Description:</strong> <?php echo $lecture_desc ?></p>
                    <hr>
                    <p><a href='<?php echo $lecture_media_link ?>' download class='btn waves-effect waves-blue blue'>Media File</a></p>
                    <p><a href='<?php echo $lecture_text_link ?> ' download class='btn waves-effect waves-blue blue'>Script File</a></p>
                    <p><a href='<?php echo $lecture_sync_link ?> ' download class='btn waves-effect waves-blue blue'>Sync File</a></p>

                    <?php
                    foreach ($lecture['trans'] as $tran) {
                        $lang = $tran['l_name'];
                        $link = $tran['trans_link'];
                        echo "\t\t\t\t<p><a href='$link' download class='btn waves-effect waves-blue blue'>Translation($lang) </a></p>\n";
                    }
                    ?>

                    <form id="download_<?php echo $modalname ?>" hidden>

                        <input type="checkbox" data-url="<?php echo $lecture_media_link ?>" checked hidden/>
                        <input type="checkbox" data-url="<?php echo $lecture_text_link ?>" checked hidden/>
                        <input type="checkbox" data-url="<?php echo $lecture_sync_link ?>" checked hidden/>
                        <?php
                        foreach ($lecture['trans'] as $tran) {
                            $link = $tran['trans_link'];
                            echo "\t\t\t\t<input type='checkbox' data-url='$link' checked hidden/>\n";
                        }
                        ?>
                    </form>

                    <div class='modal-footer'>
                        <div class="progress hide" id="progress_bar_<?php echo $modalname ?>">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;">
                            </div>
                        </div>
                        <p class="hide" id="result_<?php echo $modalname ?>"></p>
                        <button type="submit" form="download_<?php echo $modalname ?>" class="download_lecture btn green waves-effect" data-lecture-name="<?php echo$lecture_name ?>" data-lecture="<?php echo $modalname ?>">Download all</button>
                        <a href='#' class='modal-close waves-effect waves-blue btn blue'>Close</a>
                    </div>
                </div>
            </div>

            <?php
        }
    }

}

?>