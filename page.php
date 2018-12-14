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

            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
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
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <?php if (isset($_SESSION['id'])){ ?>
                        <li class="waves-effect">
                            <a class="waves-effect btn light-blue accent-3" href="profile.php?id=<?php echo $_SESSION['id']?>">Profile</a>
                        </li>
                        <li class="waves-effect">
                            <a class="waves-effect btn red lighten-2" href="logout.php">Logout</a>
                        </li>
                    <?php }else{ ?>

                        <li class="waves-effect">
                            <a class="waves-effect btn green lighten-1" href="login.php">Login</a>
                        </li>
                        <li class="waves-effect">
                            <a class="waves-effect btn green darken-1" href="register.php">Register</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>

        <ul class="sidenav" id="mobile-demo">
            <?php if (isset($_SESSION['id'])){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['id']?>">Profile</a>
                </li>
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
        <script src='js/jquery.validate.min.js'></script>
        <script src='js/additional-methods.min.js'></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>

        <script>
            $().ready(function () {
                // START OPEN
                $('.sidenav').sidenav();
            });
        </script>

        <?php
    }


    public static function page_foot(){
        ?>
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
        /*for ($i = 1; $i <= (count(scandir("Data/A-V sources")) / $page_entries)+1; $i++){
            if ((isset($_GET['page']) && $i == $_GET['page']) || $i == 1) echo "<li class='active blue'><a href='?page=$i'>$i</a></li>";
            else echo "<li class='waves-effect'><a href='?page=$i'>$i</a></li>";
        }*/
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
            $contributor = User::get_user_by_id($lecture['data']['user_id']);


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
                    <p><strong>Contributor:</strong> <a href="profile.php?id=<?php echo $contributor['id']?>"><?php echo $contributor['username']?></a></p>
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

    public static function profile_detail($id){
        $user = User::get_user_profile($id);
        //echo "<pre>";
        //var_dump($user);
        //echo "</pre>";

        ?>
        <main>
            <div class="container">
                <div class="row" style="margin-top: 3em">
                    <div class="col s4 offset-s4">
                        <div class="card" style="padding-top: 1em">
                            <div class="container">
                                <?php if($user['image'] != NULL){ ?>
                                    <img src="Pictures/Profiles/<?php echo $user['image'] ?>" class="circle" style="max-width: 100%">
                                <?php } else { ?>
                                    <img src="Pictures/Placeholders/user_placeholder.svg" style="max-width: 150px; margin: auto">
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="col s12 center-align">
                                    <h4><?php echo $user['username'] ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="fixed-action-btn">
                    <a class="btn-floating btn-large  waves-effect waves-light red lighten-1" href="profile.php?id=<?php echo $user['id'].'&edit' ?>"><i class="material-icons">edit</i></a>
                </div>
                <hr class="" style="border-color: #f0f0f0">
            </div>
            <div class="container">
                <div class="row valign-wrapper">
                    <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                        <b>Name:</b>
                    </div>
                    <div class="col s6">
                        <?php echo $user['first_name']." ".$user['last_name'] ?>
                    </div>
                </div>

                <div class="row valign-wrapper">
                    <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                        <b>Username:</b>
                    </div>
                    <div class="col s6">
                        <?php echo $user['username'] ?>
                    </div>
                </div>

                <div class="row valign-wrapper">
                    <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                        <b>E-mail:</b>
                    </div>
                    <div class="col s6">
                        <?php echo $user['email'] ?>
                    </div>
                </div>

                <div class="row valign-wrapper">
                    <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                        <b>Gender</b>
                    </div>
                    <div class="col s6">
                        <?php
                        if($user['gender'] == 'M') echo "Male";
                        else if ($user['gender'] == 'F') echo "Female";
                        else echo "";
                        ?>
                    </div>
                </div>

                <div class="row valign-wrapper">
                    <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                        <b>Age:</b>
                    </div>
                    <div class="col s6">
                        <?php echo $user['age'] ?>
                    </div>
                </div>


                <div class="row valign-wrapper">
                    <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                        <b>Native language:</b>
                    </div>
                    <div class="col s6">
                        <?php echo $user['name'] ?>
                    </div>
                </div>
            </div>
        </main>

        <?php

    }

    public static function profile_edit($id){
        $user = User::get_user_profile($id);
        //var_dump($id);

        $languages = Lecture::get_languages();
        //echo "<pre>";
        //var_dump($user);
        //var_dump($languages);
        //echo "</pre>";

        ?>
        <main>
            <form id="profile_edit" method="post" enctype="multipart/form-data" action="profile.php?id=<?php echo $user['id'] ?>">
                <div class="container">
                    <div class="row" style="margin-top: 3em">
                        <div class="col s4 offset-s4">
                            <div class="card" style="padding-top: 1em">
                                <div class="container">
                                    <?php if($user['image'] != NULL){ ?>
                                        <img src="Pictures/Profiles/<?php echo $user['image'] ?>" class="circle" style="max-width: 100%">
                                    <?php } else { ?>
                                        <img src="Pictures/Placeholders/user_placeholder.svg" style="max-width: 150px; margin: auto">
                                    <?php } ?>
                                </div>
                                <div class="file-field input-field col s8 offset-s2">
                                    <div class="btn">
                                        <span>Image</span>
                                        <input type="file" name='picture' id='picture'>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 center-align">
                                        <h4><?php echo $user['username'] ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <hr class="" style="border-color: #f0f0f0">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="input-field col s12 m3 offset-m3">
                            <input id="first_name" type="text" class="validate" data-error="#errorTxt1" name="first_name" value="<?php echo $user['first_name']?>">
                            <label for="first_name">First name</label>
                            <div class="f_error" id="errorTxt1"></div>
                        </div>
                        <div class="input-field col s12 m3">
                            <input id="last_name" type="text" class="validate" data-error="#errorTxt2" name="last_name" value="<?php echo $user['last_name'] ?>">
                            <label for="last_name">Last name</label>
                            <div class="f_error" id="errorTxt2"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6 offset-m3">
                            <input id="username" type="text" class="validate" data-error="#errorTxt3" name="username" value="<?php echo $user['username'] ?>" required disabled>
                            <label for="username">Username</label>
                            <div class="f_error" id="errorTxt3"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6 offset-m3">
                            <input id="email" type="email" class="validate" data-error="#errorTxt4" name="email" required value="<?php echo $user['email'] ?>">
                            <label for="email">E-mail address</label>
                            <div class="f_error" id="errorTxt4"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6 m3 offset-m3">
                            <input type="number" name="age" id="age" min="0" max="99" value="<?php echo $user['age'] ?>">
                            <label for="age">Age</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <select id="gender" name="gender">
                                <option value="" disabled selected>Choose gender...</option>
                                <option value="M" <?php if($user['gender'] == 'M') echo "selected" ?>>Male</option>
                                <option value="F" <?php if($user['gender'] == 'F') echo "selected" ?>>Female</option>
                            </select>
                            <label>Gender</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s12 m6 offset-m3">
                            <select id="native_lang" name="native_lang">
                                <option value="" selected disabled>Select your native language...</option>
                                <?php
                                foreach ($languages as $language){
                                    ?>
                                    <option value="<?php echo $language['id'] ?>" <?php if($user['native_lang_id'] == $language['id']) echo "selected" ?>><?php echo $language['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <label>Native language</label>
                        </div>
                    </div>

                    <div class="row center-align">
                        <div class="input-field col s6 m3 offset-m3">
                            <button type="submit" class="btn green waves-effect" name="save_profile">Save changes
                                <i class="material-icons right">check</i></button>
                        </div>
                        <div class="input-field col s6 m3">
                            <a href="profile.php?id=<?php echo $user['id'] ?>" class="btn red lighten-2 waves-effect">Discard changes
                                <i class="material-icons right">clear</i></a>
                        </div>
                    </div>

                </div>
            </form>
        </main>
        <?php

    }

    public static function lecture_add(){

        $languages = Lecture::get_languages();

        ?>
        <main>
            <form id="lecture_add" method="post" enctype="multipart/form-data" action="lecture_add.php">
                <div class="container">

                    <div class="row" style="margin-top: 3em">
                        <div class="input-field col s12 m8 offset-m2">
                            <label for="lecture_title">Lecture title</label>
                            <input type="text" id="lecture_title" name="lecture_title" required value="<?php if(isset($_POST['lecture_title'])) echo $_POST['lecture_title'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m8 offset-m2">
                            <textarea id="lecture_description" class="materialize-textarea" name="lecture_description"><?php if(isset($_POST['lecture_description'])) echo $_POST['lecture_description']?></textarea>
                            <label for="lecture_description">Lecture Description</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6 m4 offset-m2">
                            <select id="lecture_diff" name="lecture_diff">
                                <option value="" selected disabled>Choose level of difficulty...</option>
                                <option value="1" <?php if(isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 1) echo "selected" ?>>1</option>
                                <option value="2" <?php if(isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 2) echo "selected" ?>>2</option>
                                <option value="3" <?php if(isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 3) echo "selected" ?>>3</option>
                            </select>
                            <label>Difficulty level</label>
                        </div>
                        <div class="input-field col s6 m4">
                            <select id="lecture_lang" name="lecture_lang">
                                <option value="" selected disabled>Select language...</option>
                                <?php
                                foreach ($languages as $language){
                                    ?>
                                    <option value="<?php echo $language['id'] ?>" <?php if(isset($_POST['lecture_lang'])&&$_POST['lecture_lang'] == $language['id']) echo "selected" ?>><?php echo $language['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <label>Base language</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="file-field input-field col s12 m8 offset-m2">
                            <div class="btn">
                                <span>Media</span>
                                <input type="file" name='lecture_media' id='lecture_media' accept="audio/*" required>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="file-field input-field col s12 m8 offset-m2">
                            <div class="btn">
                                <span>Script</span>
                                <input type="file" name='lecture_script' id='lecture_script' accept=".txt" required>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="file-field input-field col s12 m8 offset-m2">
                            <div class="btn">
                                <span>Sync</span>
                                <input type="file" name='lecture_sync' id='lecture_sync' accept=".mbpsf" required>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                    </div>

                    <div id="translations">
                    </div>

                    <input hidden id="trans_count" type="number" name="trans_count" value="0">

                    <div class="row center-align">
                        <div class="col s2 offset-s5">
                            <p>Add translation</p>
                            <a class="btn-floating green btn-large" id="add_translation"><i class="material-icons right">add</i></a>
                        </div>
                    </div>
                    <div class="row center-align">
                        <div class="input-field col s6 m3 offset-m3">
                            <button type="submit" class="btn green waves-effect" name="save_lecture">Save lecture
                                <i class="material-icons right">check</i></button>
                        </div>
                        <div class="input-field col s6 m3">
                            <a href="index.php" class="btn red lighten-2 waves-effect">Discard lecture
                                <i class="material-icons right">clear</i></a>
                        </div>
                    </div>

                </div>
            </form>
        </main>
        <?php
    }



}

?>