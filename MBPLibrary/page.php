<?php

/**
 * @author Martin Hrebeňár
 */

include_once('lecture.php');
include_once('user.php');

class Page{

/**
 * @param String $title , title of the page
 * function inserts head of the page
 */
public static function page_header($title){
?>
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
          integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" type="text/css" href="css/materialize.css">
    <script src="js/materialize.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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

/**
 *  function inserts navigation bar to page
 */
public static function page_navbar(){
?>
<body>
<header>
    <nav>
        <div class="nav-wrapper blue" style="padding: 0 2em;">
            <a href="index.php" class="brand-logo">MBP - Library</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li>
                    <a class="waves-effect btn grey lighten-2 black-text" href="../index.html">Application</a>
                </li>
                <?php if (isset($_SESSION['id'])) { ?>
                    <li>
                        <a class="waves-effect btn light-blue accent-3"
                           href="profile.php?id=<?php echo $_SESSION['id'] ?>">Profile</a>
                    </li>
                    <li>
                        <a class="waves-effect btn red lighten-2" href="logout.php">Logout</a>
                    </li>
                    <?php if ($_SESSION['admin'] == 1) { ?>
                        <li>
                            <a class="waves-effect btn red white-text text-darken-2 accent-3" href="admin.php">Admin
                                Zone</a>
                        </li>
                    <?php }
                } else { ?>

                    <li>
                        <a class="waves-effect btn green lighten-1" href="login.php">Login</a>
                    </li>
                    <li>
                        <a class="waves-effect btn green darken-1" href="register.php">Register</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <li class="nav-item">
            <a class="nav-link" href="../index.html">Application</a>
        </li>
        <?php if (isset($_SESSION['id'])) { ?>
            <li class="nav-item">
                <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['id'] ?>">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        <?php } else { ?>

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

/**
 *  function inserts footer to page
 */
public static function page_footer()
{
    ?>
    <footer class='page-footer blue'>
        <div class="container">
            <div class="row center-align ">
                <div class="col s4 m2"><a class="grey-text text-lighten-3" href="faq.php">FAQ</a></div>
                <div class="col s4 m2"><a class="grey-text text-lighten-3" href="contacts.php">Contacts</a></div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                version 1.3.0 | &copy; Created by 'Prvá skupina v zozname' as a school project, 2019
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


/**
 *  function inserts closing tags for page
 */
public static function page_foot(){
?>
</body>
</html>
<?php
}

/**
 *  lists all lections from database, also takes care of pagination
 */
public static function list_lectures(){
$page_entries = 5;
$start_from = 0;
if (isset($_GET['page'])) $start_from = ($_GET['page'] - 1) * $page_entries;

$avail_langs = Lecture::get_avail_langs();

if (isset($_POST['filter_sent'])) {
    $_SESSION['is_filtered'] = true;
    $_SESSION['filter_language'] = $_POST['filter_language'];
    $_SESSION['filter_diff'] = $_POST['filter_diff'];
    $_SESSION['filter_order'] = $_POST['filter_order'];
} elseif (isset($_POST['filter_reset']) || !isset($_GET['page'])) {
    unset($_SESSION['is_filtered']);
    unset($_SESSION['filter_language']);
    unset($_SESSION['filter_diff']);
    unset($_SESSION['filter_order']);
}

?>
<div class='container'>

    <form id="lecture_filter" method="post" action="index.php">
        <div class="row">
            <div class="input-field col s6 m3">
                <select id="filter_language" name="filter_language" required>
                    <option selected value="Def">All</option>
                    <?php
                    foreach ($avail_langs as $alang) {
                        if (isset($_SESSION['filter_language']) && $_SESSION['filter_language'] == $alang['id']) echo "<option value='" . $alang['id'] . "' selected>" . $alang['name'] . "</option>";
                        else echo "<option value='" . $alang['id'] . "'>" . $alang['name'] . "</option>";
                    }
                    ?>
                </select>
                <label>Language</label>
            </div>
            <div class="input-field col s6 m3">
                <select id="filter_diff" name="filter_diff" required>
                    <option selected value="Def">All</option>
                    <option value="1" <?php if (isset($_SESSION['filter_diff']) && $_SESSION['filter_diff'] == 1) echo "selected" ?>>
                        A
                    </option>
                    <option value="2" <?php if (isset($_SESSION['filter_diff']) && $_SESSION['filter_diff'] == 2) echo "selected" ?>>
                        B
                    </option>
                    <option value="3" <?php if (isset($_SESSION['filter_diff']) && $_SESSION['filter_diff'] == 3) echo "selected" ?>>
                        C
                    </option>
                </select>
                <label>Difficulty</label>
            </div>
            <div class="input-field col s6 m3">
                <select id="filter_order" name="filter_order" required>
                    <option selected value="Def">Default</option>
                    <option value="1" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 1) echo "selected" ?>>
                        (A-Z)
                    </option>
                    <option value="2" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 2) echo "selected" ?>>
                        (Z-A)
                    </option>
                    <option value="3" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 3) echo "selected" ?>>
                        Downloads(Asc)
                    </option>
                    <option value="4" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 4) echo "selected" ?>>
                        Downloads(Desc)
                    </option>
                    <option value="5" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 5) echo "selected" ?>>
                        Date(Oldest)
                    </option>
                    <option value="6" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 6) echo "selected" ?>>
                        Date(Newest)
                    </option>
                </select>
                <label>Order By</label>
            </div>
            <div class="input-field col s6 m2">
                <button type="submit" class="btn blue darken-4" id="filter_sent" name="filter_sent">Apply filter
                </button>
            </div>
            <div class="input-field col s6 m1">
                <button type="submit" class="btn red darken-4" id="filter_reset" name="filter_reset">Reset
                </button>
            </div>
        </div>
    </form>
</div>

<?php
$lectures_count = Lecture::get_lectures_count();

if (isset($_SESSION['is_filtered'])) {
    $lang = $_SESSION['filter_language'];
    $diff = $_SESSION['filter_diff'];
    $ord = $_SESSION['filter_order'];
    $lectures = Lecture::get_lectures_filtered($start_from, $page_entries, $lang, $diff, $ord);
    $lectures_count = Lecture::get_lectures_filtered_count($lang, $diff, $ord);

} else $lectures = Lecture::get_lectures($start_from, $page_entries);

if ($lectures == null) {
    self::warning_card("No articles found.");
    return;
}
?>

<div class="container">
    <table class='responsive-table'>
        <thead>
        <tr>
            <th>#</th>
            <th>Article name</th>
            <th>Article Language</th>
            <th>Difficulty</th>
            <th>Length</th>
            <th>Downloads</th>
            <th></th>
        </tr>
        </thead>
        <?php

        echo "<tbody>";
        foreach ($lectures as $lecture) {

            $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name']);
            $lecture_name = $lecture['data']['name'];
            $lecture_id = $lecture['data']['id'];
            $lecture_lang = $lecture['data']['l_name'];
            $lecture_diff = $lecture['data']['difficulty'];
            $lecture_down_count = $lecture['data']['download_count'];

            echo "<tr>";
            echo "<td>$lecture_id</td>";
            echo "<td>$lecture_name</td>";
            echo "<td>$lecture_lang</td>";
            if ($lecture_diff == 1) echo "<td>A</td>";
            elseif ($lecture_diff == 2) echo "<td>B</td>";
            else echo "<td>C</td>";
            echo "<td></td>";
            echo "<td>$lecture_down_count x</td>";
            echo "<td><a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'><i class='fa fa-search'></i></a></td>";
            echo "</tr>";

        }


        echo "</tbody></table><ul class='pagination'>";
        if (!isset($_GET['page']) || $_GET['page'] == 1) echo "<li class='disabled'><a href='#!'><i class='material-icons'>chevron_left</i></a></li>";
        else echo "<li class='waves-effect'><a href='?page=" . ($_GET['page'] - 1) . "'><i class='material-icons'>chevron_left</i></a></li>";
        for ($i = 1; $i < ($lectures_count / $page_entries) + 1; $i++) {
            if ((isset($_GET['page']) && $i == $_GET['page']) || (!isset($_GET['page']) && $i == 1)) echo "<li class='active blue'><a href='?page=$i'>$i</a></li>";
            else echo "<li class='waves-effect'><a href='?page=$i'>$i</a></li>";
        }
        if ((isset($_GET['page']) && $_GET['page'] == ($lectures_count / $page_entries)) || (!isset($_GET['page']) && ($lectures_count / $page_entries) <= 1)) echo "<li class='disabled'><a href='#!'><i class='material-icons'>chevron_right</i></a></li>";
        elseif (!isset($_GET['page']) && ($lectures_count / $page_entries) > 1) echo "<li class='waves-effect'><a href='?page=2'><i class='material-icons'>chevron_right</i></a></li>";
        else echo "<li class='waves-effect'><a href='?page=" . ($_GET['page'] + 1) . "'><i class='material-icons'>chevron_right</i></a></li>";
        echo "</ul>";
        echo "</div>";

        foreach ($lectures as $lecture) {
            $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name']);
            $lecture_desc = $lecture['data']['description'];
            $lecture_id = $lecture['data']['id'];
            $lecture_media_link = $lecture['data']['audio_link'];
            $lecture_text_link = $lecture['data']['text_link'];
            $lecture_sync_link = $lecture['data']['sync_file_link'];
            $lecture_lang = $lecture['data']['l_name'];
            $lecture_name = $lecture['data']['name'];
            $contributor = User::get_user_by_id($lecture['data']['user_id']);

            if (isset($_SESSION['id'])) {
                $is_starred = Lecture::is_starred($lecture_id, $_SESSION['id']);
            }


            /*print "<pre>";
            print_r($lecture);
            print "</pre>";*/
            ?>
            <div class='modal' id='<?php echo $modalname ?>'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4><?php echo $lecture_name ?></h4>
                        <?php if (isset($_SESSION['id'])) { ?>
                            <?php if ($_SESSION['id'] != $contributor['id']) { ?><a href="#!" class='star_btn'
                                                                                    data-lectureid="<?php echo $lecture_id ?>"
                                                                                    data-userid="<?php echo $_SESSION['id'] ?>"
                                                                                    style='position: fixed; right: 5em; top: 0.5em; font-size: 2em;'>
                                <i class="material-icons"
                                   id="star_icon_<?php echo $lecture_id ?>"><?php if ($is_starred) echo "star" ?><?php if (!$is_starred) echo "star_border" ?></i>
                                </a><?php } ?>
                            <a href='lecture_edit.php?lid=<?php echo $lecture_id ?>' class=''
                               style='position: fixed; right: 3em; top: 0.5em; font-size: 2em;'><i
                                        class="material-icons">edit</i></a>
                        <?php } ?>
                        <a href='#' class='modal-close'
                           style='position: fixed; right: 1em; top: 0.5em; font-size: 2em;'>&times;</a></div>

                    <p><strong>Language:</strong> <?php echo $lecture_lang ?></p>
                    <p><strong>Contributor:</strong> <a
                                href="profile.php?id=<?php echo $contributor['id'] ?>"><?php echo $contributor['username'] ?></a>
                    </p>
                    <p><strong>Description:</strong> <?php echo $lecture_desc ?></p>
                    <hr>
                    <h5>Downloads</h5>
                    <?php if (file_exists($lecture_media_link)) { ?><p><a href='<?php echo $lecture_media_link ?>'
                                                                          download
                                                                          class='btn waves-effect waves-blue blue'>Audio
                            File</a></p><?php } ?>
                    <?php if (file_exists($lecture_text_link)) { ?><p><a href='<?php echo $lecture_text_link ?> '
                                                                         download
                                                                         class='btn waves-effect waves-blue blue'>Original
                            script File</a></p><?php } ?>
                    <?php if (file_exists($lecture_sync_link)) { ?><p><a href='<?php echo $lecture_sync_link ?> '
                                                                         download
                                                                         class='btn waves-effect waves-blue blue'>Sync
                            file</a></p><?php } ?>

                    <?php
                    if ($lecture['trans'] != "") {
                        foreach ($lecture['trans'] as $tran) {
                            $lang = $tran['l_name'];
                            $link = $tran['trans_link'];
                            if (file_exists($lecture_media_link)) {
                                echo "\t\t\t\t<p><a href='$link' download class='btn waves-effect waves-blue blue'>Parallel translation ( $lang ) </a></p>\n";
                            }
                        }
                    }
                    ?>

                    <form id="download_<?php echo $modalname ?>" hidden>

                        <input type="checkbox" data-url="<?php echo $lecture_media_link ?>" checked hidden/>
                        <?php if (file_exists($lecture_text_link)) { ?><input type="checkbox"
                                                                              data-url="<?php echo $lecture_text_link ?>"
                                                                              checked hidden/><?php } ?>
                        <?php if (file_exists($lecture_sync_link)) { ?><input type="checkbox"
                                                                              data-url="<?php echo $lecture_sync_link ?>"
                                                                              checked hidden/><?php } ?>
                        <?php

                        if ($lecture['trans'] != "") {
                            foreach ($lecture['trans'] as $tran) {
                                $link = $tran['trans_link'];
                                if (file_exists($lecture_media_link)) {
                                    echo "\t\t\t\t<input type='checkbox' data-url='$link' checked hidden/>\n";
                                }
                            }
                        }

                        ?>
                    </form>

                    <div class='modal-footer'>

                        <div class="progress" id="progress_bar_<?php echo $modalname ?>" style="display: none">
                            <div class="indeterminate"></div>
                        </div>
                        <p class="hide" id="result_<?php echo $modalname ?>"></p>
                        <button type="submit" form="download_<?php echo $modalname ?>"
                                class="download_lecture btn green waves-effect"
                                data-lecture-id="<?php echo $lecture_id ?>"
                                data-lecture-name="<?php echo $lecture_name ?>" data-lecture="<?php echo $modalname ?>">
                            Download all
                        </button>
                        <a href='#' class='modal-close waves-effect waves-blue btn blue'>Close</a>

                    </div>
                </div>
            </div>

            <?php
        }
        }

        /**
         * @param String $warning - message to be printed in card
         * inserts an warning card with error message
         */
        public static function warning_card($warning)
        {
            ?>
            <main style="margin: 3em 0">
                <div class="container">
                    <div class="row valign-wrapper" style="padding: 5em">
                        <div class="col s12">
                            <div class="card orange lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title">Warning</span>
                                    <p><?php echo $warning ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="index.php" class="grey-text text-lighten-2">Go HOME</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
        }

        /**
         * @param int $id - id of profile
         * prints profile for user or error card if user can not be found
         */
        public static function profile_detail($id)
        {
            $user = User::get_user_profile($id);
            if ($user == NULL) {
                self::error_card("User with this ID does not exist.");
            } else {
                ?>
                <main style="margin: 3em 0">
                    <?php
                    if (isset($_SESSION['msg'])) {
                        Page::page_message($_SESSION['msg_status'], $_SESSION['msg']);
                        unset($_SESSION['msg']);
                        unset($_SESSION['msg_status']);
                    }
                    ?>
                    <div class="container">
                        <div class="row" style="margin-top: 3em">
                            <div class="col s4 offset-s4">
                                <div class="card center-align" style="padding-top: 1em">
                                    <div class="container">
                                        <?php if ($user['image'] != NULL) { ?>
                                            <img src="Pictures/Profiles/<?php echo $user['image'] ?>" class="circle"
                                                 style="max-width: 100%">
                                        <?php } else { ?>
                                            <img src="Pictures/Placeholders/user_placeholder.svg"
                                                 style="max-width: 150px; margin: auto">
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
                            <a class="btn-floating btn-large pulse waves-effect waves-light red lighten-1"
                               href="profile.php?id=<?php echo $user['id'] . '&edit' ?>"><i
                                        class="material-icons">edit</i></a>
                        </div>
                        <hr class="" style="border-color: #f0f0f0">
                    </div>
                    <div class="container">
                        <div class="row valign-wrapper">
                            <div class="col s6 right-align" style="border-right: 1px dotted #f0f0f0">
                                <b>Name:</b>
                            </div>
                            <div class="col s6">
                                <?php echo $user['first_name'] . " " . $user['last_name'] ?>
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
                                if ($user['gender'] == 'M') echo "Male";
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
                        <hr>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col s6">
                                <h4>Contributions</h4>
                                <?php
                                self::user_lectures($_GET['id']);
                                ?>
                            </div>
                            <div class="col s6">
                                <h4>Favorites</h4>
                                <?php
                                self::user_favorite_lectures($_GET['id']);
                                ?>
                            </div>
                        </div>
                    </div>

                </main>

                <?php
            }
        }

        /**
         * @param String $err - message to be printed in card
         * inserts an error card with error message
         */
        public static function error_card($err)
        {
            ?>
            <main style="margin: 3em 0">
                <div class="container">
                    <div class="row" style="padding: 5em">
                        <div class="col s12">
                            <div class="card red lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title">Error</span>
                                    <p><?php echo $err ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="index.php" class="grey-text text-lighten-2">Go HOME</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
        }

        /**
         * @param String $status - from [ 'OK', 'ERR', 'ERR'], defines type of message
         * @param String $msg - message to be printed
         * initialize SESSION parameters so next page that can process message will show it to user
         */
        public static function page_message($status, $msg)
        {
            $color = "";
            //var_dump($status);
            if (strcmp($status, "OK") == 0) $color = "green";
            else if (strcmp($status, "WAR") == 0) $color = "orange";
            else if (strcmp($status, "ERR") == 0) $color = "red";

            ?>
            <div class="container lighten-1 <?php echo $color ?>">
                <div class="row">
                    <div class="col s10 offset-s1 white-text" style="padding: 1em;">
                        <p><?php echo $msg ?></p>
                    </div>
                </div>
            </div>
            <?php
        }

        public static function user_lectures($user_id)
        {
            $lectures = Lecture::get_user_lectures($user_id);

            $rng = random_bytes(4);

            if ($lectures == Null) {
                self::warning_card("No articles found");
                return;
            }

            ?>
            <table class='responsive-table'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Article name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($lectures as $lecture) {
                    $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name'] . $rng);
                    $lecture_name = $lecture['data']['name'];
                    $lecture_id = $lecture['data']['id'];

                    echo "<tr>";
                    echo "<td>$lecture_id</td>";
                    echo "<td>$lecture_name</td>";

                    if ($_GET['id'] == $_SESSION['id'] || $_SESSION['admin'] == 1) echo "<td> <button data-swal_id='swal_$modalname' data-swal_lec_id='$lecture_id' class='waves-effect waves-blue btn red swalbtn'><i class='fa fa-times'></i></button> <a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'><i class='fa fa-search'></i></a></td>";
                    else echo "<td><a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'><i class='fa fa-search'></i></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <?php
            foreach ($lectures as $lecture) {
                $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name'] . $rng);
                $lecture_desc = $lecture['data']['description'];
                $lecture_id = $lecture['data']['id'];
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
                            <?php if (isset($_SESSION['id'])) { ?>
                                <a href='#' class=''
                                   style='position: fixed; right: 3em; top: 0.5em; font-size: 2em;'><i
                                            class="material-icons">edit</i></a>
                            <?php } ?>
                            <a href='#' class='modal-close'
                               style='position: fixed; right: 1em; top: 0.5em; font-size: 2em;'>&times;</a></div>


                        <p><strong>Language:</strong> <?php echo $lecture_lang ?></p>
                        <p><strong>Contributor:</strong> <a
                                    href="profile.php?id=<?php echo $contributor['id'] ?>"><?php echo $contributor['username'] ?></a>
                        </p>
                        <p><strong>Description:</strong> <?php echo $lecture_desc ?></p>
                        <hr>
                        <h5>Download:</h5>
                        <?php if (file_exists($lecture_media_link)) { ?><p><a href='<?php echo $lecture_media_link ?>'
                                                                              download
                                                                              class='btn waves-effect waves-blue blue'>Audio
                                File</a></p><?php } ?>
                        <?php if (file_exists($lecture_text_link)) { ?><p><a href='<?php echo $lecture_text_link ?> '
                                                                             download
                                                                             class='btn waves-effect waves-blue blue'>Original
                                script File</a></p><?php } ?>
                        <?php if (file_exists($lecture_sync_link)) { ?><p><a href='<?php echo $lecture_sync_link ?> '
                                                                             download
                                                                             class='btn waves-effect waves-blue blue'>Sync
                                file</a></p><?php } ?>

                        <?php
                        if ($lecture['trans'] != "") {
                            foreach ($lecture['trans'] as $tran) {
                                $lang = $tran['l_name'];
                                $link = $tran['trans_link'];
                                if (file_exists($lecture_media_link)) {
                                    echo "\t\t\t\t<p><a href='$link' download class='btn waves-effect waves-blue blue'>Parallel translation ( $lang ) </a></p>\n";
                                }
                            }
                        }
                        ?>

                        <form id="download_<?php echo $modalname ?>" hidden>

                            <input type="checkbox" data-url="<?php echo $lecture_media_link ?>" checked hidden/>
                            <?php if (file_exists($lecture_text_link)) { ?><input type="checkbox"
                                                                                  data-url="<?php echo $lecture_text_link ?>"
                                                                                  checked hidden/><?php } ?>
                            <?php if (file_exists($lecture_sync_link)) { ?><input type="checkbox"
                                                                                  data-url="<?php echo $lecture_sync_link ?>"
                                                                                  checked hidden/><?php } ?>
                            <?php

                            if ($lecture['trans'] != "") {
                                foreach ($lecture['trans'] as $tran) {
                                    $link = $tran['trans_link'];
                                    if (file_exists($lecture_media_link)) {
                                        echo "\t\t\t\t<input type='checkbox' data-url='$link' checked hidden/>\n";
                                    }
                                }
                            }

                            ?>
                        </form>

                        <div class='modal-footer'>

                            <div class="progress" id="progress_bar_<?php echo $modalname ?>" style="display: none">
                                <div class="indeterminate"></div>
                            </div>
                            <p class="hide" id="result_<?php echo $modalname ?>"></p>
                            <button type="submit" form="download_<?php echo $modalname ?>"
                                    class="download_lecture btn green waves-effect"
                                    data-lecture-id="<?php echo $lecture_id ?>"
                                    data-lecture-name="<?php echo $lecture_name ?>"
                                    data-lecture="<?php echo $modalname ?>">Download all
                            </button>
                            <a href='#' class='modal-close waves-effect waves-blue btn blue'>Close</a>

                        </div>
                    </div>
                </div>

                <?php
            }
        }

        public static function user_favorite_lectures($user_id)
        {
            $lectures = Lecture::get_user_favorite_lectures($user_id);

            $rng = random_bytes(4);

            if ($lectures == Null) {
                self::warning_card("No articles found");
                return;
            }

            ?>
            <table class='responsive-table'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Article name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($lectures as $lecture) {
                    $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name'] . $rng);
                    $lecture_name = $lecture['data']['name'];
                    $lecture_id = $lecture['data']['id'];
                    $contributor = User::get_user_by_id($lecture['data']['user_id']);

                    echo "<tr>";
                    echo "<td>$lecture_id</td>";
                    echo "<td>$lecture_name</td>";

                    if ($_SESSION['admin'] == 1) echo "<td> <button data-swal_id='swal_$modalname' data-swal_lec_id='$lecture_id' class='waves-effect waves-blue btn red swalbtn'><i class='fa fa-times'></i></button> <a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'><i class='fa fa-search'></i></a></td>";
                    else echo "<td><a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'><i class='fa fa-search'></i></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <?php
            foreach ($lectures as $lecture) {
                $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name'] . $rng);
                $lecture_desc = $lecture['data']['description'];
                $lecture_id = $lecture['data']['id'];
                $lecture_media_link = $lecture['data']['audio_link'];
                $lecture_text_link = $lecture['data']['text_link'];
                $lecture_sync_link = $lecture['data']['sync_file_link'];
                $lecture_lang = $lecture['data']['l_name'];
                $lecture_name = $lecture['data']['name'];
                $contributor = User::get_user_by_id($lecture['data']['user_id']);

                if (isset($_SESSION['id'])) {
                    $is_starred = Lecture::is_starred($lecture_id, $_SESSION['id']);
                }

                ?>
                <div class='modal' id='<?php echo $modalname ?>'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h4><?php echo $lecture_name ?></h4>
                            <?php if (isset($_SESSION['id'])) { ?>
                                <?php if ($_SESSION['id'] != $contributor['id']) { ?><a href="#!" class='star_btn'
                                                                                        data-lectureid="<?php echo $lecture_id ?>"
                                                                                        data-userid="<?php echo $_SESSION['id'] ?>"
                                                                                        style='position: fixed; right: 5em; top: 0.5em; font-size: 2em;'>
                                    <i class="material-icons"
                                       id="star_icon_<?php echo $lecture_id ?>"><?php if ($is_starred) echo "star" ?><?php if (!$is_starred) echo "star_border" ?></i>
                                    </a><?php } ?>
                                <a href='#' class=''
                                   style='position: fixed; right: 3em; top: 0.5em; font-size: 2em;'><i
                                            class="material-icons">edit</i></a>
                            <?php } ?>
                            <a href='#' class='modal-close'
                               style='position: fixed; right: 1em; top: 0.5em; font-size: 2em;'>&times;</a></div>


                        <p><strong>Language:</strong> <?php echo $lecture_lang ?></p>
                        <p><strong>Contributor:</strong> <a
                                    href="profile.php?id=<?php echo $contributor['id'] ?>"><?php echo $contributor['username'] ?></a>
                        </p>
                        <p><strong>Description:</strong> <?php echo $lecture_desc ?></p>
                        <hr>
                        <h5>Download:</h5>
                        <?php if (file_exists($lecture_media_link)) { ?><p><a href='<?php echo $lecture_media_link ?>'
                                                                              download
                                                                              class='btn waves-effect waves-blue blue'>Audio
                                File</a></p><?php } ?>
                        <?php if (file_exists($lecture_text_link)) { ?><p><a href='<?php echo $lecture_text_link ?> '
                                                                             download
                                                                             class='btn waves-effect waves-blue blue'>Original
                                script
                                File</a></p><?php } ?>
                        <?php if (file_exists($lecture_sync_link)) { ?><p><a href='<?php echo $lecture_sync_link ?> '
                                                                             download
                                                                             class='btn waves-effect waves-blue blue'>Sync
                                file</a></p><?php } ?>

                        <?php
                        if ($lecture['trans'] != "") {
                            foreach ($lecture['trans'] as $tran) {
                                $lang = $tran['l_name'];
                                $link = $tran['trans_link'];
                                if (file_exists($lecture_media_link)) {
                                    echo "\t\t\t\t<p><a href='$link' download class='btn waves-effect waves-blue blue'>Parallel translation ( $lang ) </a></p>\n";
                                }
                            }
                        }
                        ?>

                        <form id="download_<?php echo $modalname ?>" hidden>

                            <input type="checkbox" data-url="<?php echo $lecture_media_link ?>" checked hidden/>
                            <?php if (file_exists($lecture_text_link)) { ?><input type="checkbox"
                                                                                  data-url="<?php echo $lecture_text_link ?>"
                                                                                  checked hidden/><?php } ?>
                            <?php if (file_exists($lecture_sync_link)) { ?><input type="checkbox"
                                                                                  data-url="<?php echo $lecture_sync_link ?>"
                                                                                  checked hidden/><?php } ?>
                            <?php

                            if ($lecture['trans'] != "") {
                                foreach ($lecture['trans'] as $tran) {
                                    $link = $tran['trans_link'];
                                    if (file_exists($lecture_media_link)) {
                                        echo "\t\t\t\t<input type='checkbox' data-url='$link' checked hidden/>\n";
                                    }
                                }
                            }

                            ?>
                        </form>

                        <div class='modal-footer'>

                            <div class="progress" id="progress_bar_<?php echo $modalname ?>" style="display: none">
                                <div class="indeterminate"></div>
                            </div>
                            <p class="hide" id="result_<?php echo $modalname ?>"></p>
                            <button type="submit" form="download_<?php echo $modalname ?>"
                                    class="download_lecture btn green waves-effect"
                                    data-lecture-id="<?php echo $lecture_id ?>"
                                    data-lecture-name="<?php echo $lecture_name ?>"
                                    data-lecture="<?php echo $modalname ?>">Download all
                            </button>
                            <a href='#' class='modal-close waves-effect waves-blue btn blue'>Close</a>

                        </div>
                    </div>
                </div>

                <?php
            }
        }

        /**
         * @param int $id - id of profile
         * prints form for profile editing, or prints error car if user cannot be found
         */
        public static function profile_edit($id)
        {
            $user = User::get_user_profile($id);
            //var_dump($id);

            $languages = Lecture::get_languages();
            //echo "<pre>";
            //var_dump($user);
            //var_dump($languages);
            //echo "</pre>";
            if ($user == NULL) {
                self::error_card("User with this ID does not exist.");
            } else {
                ?>
                <main style="margin: 3em 0">
                    <form id="profile_edit" method="post" enctype="multipart/form-data"
                          action="profile.php?id=<?php echo $user['id'] ?>">
                        <div class="container">
                            <div class="row" style="margin-top: 3em">
                                <div class="col s4 offset-s4">
                                    <div class="card center-align" style="padding-top: 1em">
                                        <div class="container">
                                            <?php if ($user['image'] != NULL) { ?>
                                                <img src="Pictures/Profiles/<?php echo $user['image'] ?>" class="circle"
                                                     style="max-width: 100%">
                                            <?php } else { ?>
                                                <img src="Pictures/Placeholders/user_placeholder.svg"
                                                     style="max-width: 150px; margin: auto">
                                            <?php } ?>
                                        </div>
                                        <div class="file-field input-field col s8 offset-s2">
                                            <div class="btn">
                                                <span>Image</span>
                                                <input type="file" name='picture' id='picture' accept="image/jpeg">
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
                                    <input id="first_name" type="text" class="validate" data-error="#errorTxt1"
                                           name="first_name" value="<?php echo $user['first_name'] ?>">
                                    <label for="first_name">First name</label>
                                    <div class="f_error" id="errorTxt1"></div>
                                </div>
                                <div class="input-field col s12 m3">
                                    <input id="last_name" type="text" class="validate" data-error="#errorTxt2"
                                           name="last_name" value="<?php echo $user['last_name'] ?>">
                                    <label for="last_name">Last name</label>
                                    <div class="f_error" id="errorTxt2"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 offset-m3">
                                    <input id="username" type="text" class="validate" data-error="#errorTxt3"
                                           name="username" value="<?php echo $user['username'] ?>" required disabled>
                                    <label for="username">Username</label>
                                    <div class="f_error" id="errorTxt3"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 offset-m3">
                                    <input id="email" type="email" class="validate" data-error="#errorTxt4" name="email"
                                           required value="<?php echo $user['email'] ?>">
                                    <label for="email">E-mail address</label>
                                    <div class="f_error" id="errorTxt4"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s6 m3 offset-m3">
                                    <input type="number" name="age" id="age" min="0" max="99"
                                           value="<?php echo $user['age'] ?>">
                                    <label for="age">Age</label>
                                </div>
                                <div class="input-field col s6 m3">
                                    <select id="gender" name="gender">
                                        <option value="" disabled selected>Choose gender...</option>
                                        <option value="M" <?php if ($user['gender'] == 'M') echo "selected" ?>>Male
                                        </option>
                                        <option value="F" <?php if ($user['gender'] == 'F') echo "selected" ?>>Female
                                        </option>
                                    </select>
                                    <label>Gender</label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="input-field col s12 m6 offset-m3">
                                    <select id="native_lang" name="native_lang">
                                        <option value="" selected disabled>Select your native language...</option>
                                        <?php
                                        foreach ($languages as $language) {
                                            ?>
                                            <option value="<?php echo $language['id'] ?>" <?php if ($user['native_lang_id'] == $language['id']) echo "selected" ?>><?php echo $language['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <label>Native language</label>
                                </div>
                            </div>

                            <div class="row center-align">
                                <div class="input-field col s6 m3 offset-m3">
                                    <button type="submit" class="btn green waves-effect" name="save_profile">Save
                                        changes
                                        <i class="material-icons right">check</i></button>
                                </div>
                                <div class="input-field col s6 m3">
                                    <a href="profile.php?id=<?php echo $user['id'] ?>"
                                       class="btn red lighten-2 waves-effect">Discard changes
                                        <i class="material-icons right">clear</i></a>
                                </div>
                            </div>

                        </div>
                    </form>
                </main>
                <?php
            }
        }

        /**
         *  prints form for creating a new lecture
         */
        public static function lecture_add()
        {

            $languages = Lecture::get_languages();
            ?>
            <main style="margin: 3em 0">
                <form id="lecture_add" method="post" enctype="multipart/form-data" action="lecture_add.php">
                    <div class="container">

                        <div class="row" style="margin-top: 3em">
                            <div class="input-field col s12 m8 offset-m2">
                                <label for="lecture_title">Article title</label>
                                <input type="text" id="lecture_title" name="lecture_title" required
                                       value="<?php if (isset($_POST['lecture_title'])) echo $_POST['lecture_title'] ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m8 offset-m2">
                                <textarea id="lecture_description" class="materialize-textarea"
                                          name="lecture_description"><?php if (isset($_POST['lecture_description'])) echo $_POST['lecture_description'] ?></textarea>
                                <label for="lecture_description">Article Description</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s6 m4 offset-m2">
                                <select id="lecture_diff" name="lecture_diff" required>
                                    <option value="" selected disabled>Choose level of difficulty...</option>
                                    <option value="1" <?php if (isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 1) echo "selected" ?>>
                                        A
                                    </option>
                                    <option value="2" <?php if (isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 2) echo "selected" ?>>
                                        B
                                    </option>
                                    <option value="3" <?php if (isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 3) echo "selected" ?>>
                                        C
                                    </option>
                                </select>
                                <label>Difficulty level</label>
                            </div>
                            <div class="input-field col s6 m4">
                                <select id="lecture_lang" name="lecture_lang">
                                    <option value="" selected disabled>Select language...</option>
                                    <?php
                                    foreach ($languages as $language) {
                                        ?>
                                        <option value="<?php echo $language['id'] ?>" <?php if (isset($_POST['lecture_lang']) && $_POST['lecture_lang'] == $language['id']) echo "selected" ?>><?php echo $language['name'] ?></option>
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
                                    <span>Audio</span>
                                    <input type="file" name='lecture_media' id='lecture_media' accept="audio/*"
                                           required>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="file-field input-field col s12 m8 offset-m2">
                                <div class="btn">
                                    <span>Original script</span>
                                    <input type="file" name='lecture_script' id='lecture_script' accept=".txt">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="file-field input-field col s12 m8 offset-m2">
                                <div class="btn">
                                    <span>Sync file</span>
                                    <input type="file" name='lecture_sync' id='lecture_sync' accept=".mbpsf">
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
                                <a class="btn-floating green btn-large" id="add_translation"><i
                                            class="material-icons right">add</i></a>
                            </div>
                        </div>
                        <div class="row center-align">
                            <div class="input-field col s6 m3 offset-m3">
                                <button type="submit" class="btn green waves-effect" name="save_lecture">Save article
                                    <i class="material-icons right">check</i></button>
                            </div>
                            <div class="input-field col s6 m3">
                                <a href="index.php" class="btn red lighten-2 waves-effect">Discard article
                                    <i class="material-icons right">clear</i></a>
                            </div>
                        </div>

                    </div>
                </form>
            </main>
            <?php
        }

        /**
         *  prints form for creating a new lecture
         */
        public static function lecture_edit($lid)
        {

            $languages = Lecture::get_languages();

            $lec = Lecture::get_lecture_by_id($lid)[0];

            $lecture_created_by = User::get_user_by_id($lec['data']['user_id']);
            if (strlen($lec['data']['text_link']) > 10) $text_user = User::get_user_by_id($lec['data']['text_contributor_id']);
            if (strlen($lec['data']['sync_file_link']) > 10) $sync_user = User::get_user_by_id($lec['data']['sync_contributor_id']);

            ?>
            <main style="margin: 3em 0">
                <form id="lecture_edit" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="lid" value="<?php echo $lid ?>">

                    <div class="container">
                        <div class="row">
                            <div class="col s10 offset-s2">
                                <h4><?php echo $lec['data']['name'] . " by <a href='profile.php?id=" . $lecture_created_by['id'] . "'>" . $lecture_created_by['username'] . "</a>" ?></h4>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 3em">
                            <div class="input-field col s12 m8 offset-m2">
                                <label for="lecture_title">Article title</label>
                                <input type="text" id="lecture_title" name="lecture_title" required
                                       value="<?php if (isset($_POST['lecture_title'])) echo $_POST['lecture_title']; else echo $lec['data']['name'] ?>"
                                    <?php if ($_SESSION['id'] != $lec['data']['user_id'] and $_SESSION['admin'] == 0) echo "readonly" ?>>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m8 offset-m2">
                                <textarea id="lecture_description" class="materialize-textarea"
                                          name="lecture_description"
                                        <?php if ($_SESSION['id'] != $lec['data']['user_id'] and $_SESSION['admin'] == 0) echo "readonly" ?>><?php if (isset($_POST['lecture_description'])) echo $_POST['lecture_description']; else echo $lec['data']['description'] ?></textarea>
                                <label for="lecture_description">Article Description</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s6 m4 offset-m2">
                                <select id="lecture_diff" name="lecture_diff" required>
                                    <option value="" selected disabled>Choose level of difficulty...</option>
                                    <option value="1" <?php if ($_SESSION['id'] != $lec['data']['user_id'] and $_SESSION['admin'] == 0 and $lec['data']['difficulty'] != 1) echo "disabled" ?> <?php if (isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 1 or $lec['data']['difficulty'] == 1) echo "selected" ?>>
                                        A
                                    </option>
                                    <option value="2" <?php if ($_SESSION['id'] != $lec['data']['user_id'] and $_SESSION['admin'] == 0 and $lec['data']['difficulty'] != 2) echo "disabled" ?> <?php if (isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 2 or $lec['data']['difficulty'] == 2) echo "selected" ?>>
                                        B
                                    </option>
                                    <option value="3" <?php if ($_SESSION['id'] != $lec['data']['user_id'] and $_SESSION['admin'] == 0 and $lec['data']['difficulty'] != 3) echo "disabled" ?> <?php if (isset($_POST['lecture_diff']) && $_POST['lecture_diff'] == 3 or $lec['data']['difficulty'] == 3) echo "selected" ?>>
                                        C
                                    </option>
                                </select>
                                <label>Difficulty level</label>
                            </div>
                            <div class="input-field col s6 m4">
                                <select id="lecture_lang" name="lecture_lang">
                                    <option value="" selected disabled>Select language...</option>
                                    <?php
                                    foreach ($languages as $language) {
                                        ?>
                                        <option value="<?php echo $language['id'] ?>"
                                            <?php if ($_SESSION['id'] != $lec['data']['user_id'] and $_SESSION['admin'] == 0 and $lec['data']['language_id'] != $language['id']) echo "disabled" ?>
                                            <?php if (isset($_POST['lecture_lang']) && $_POST['lecture_lang'] == $language['id'] or $lec['data']['language_id'] == $language['id']) echo "selected" ?>><?php echo $language['name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <label>Base language</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s5 offset-s2">
                                <a href="<?php echo $lec['data']['audio_link'] ?>" download class="btn grey"
                                   style="width: 100%"><?php echo explode("/", $lec['data']['audio_link'])[2] ?></a>
                            </div>
                            <div class="col s4 m4">
                                <?php if ($_SESSION['admin'] != 1) { ?><span class="grey-text">You cant change audio file. If you need to change it please contact system administrator</span><?php } ?>
                            </div>
                            <?php if ($_SESSION['admin'] == 1) { ?>
                                <div class="file-field input-field col s12 m8 offset-m2">
                                    <div class="btn">
                                        <span>Audio</span>
                                        <input type="file" name='lecture_media' id='lecture_media' accept="audio/*">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row">
                            <?php if (strlen($lec['data']['text_link']) > 10) { ?>
                                <div id="lect_script_file_add_wrap">
                                    <div class="col s5 offset-s2">
                                        <a href="<?php echo $lec['data']['text_link'] ?>" download class="btn grey"
                                           style="width: 100%"> <?php echo explode("/", $lec['data']['text_link'])[2] ?></a>
                                    </div>
                                    <?php if ($text_user['id'] == $_SESSION['id'] or $_SESSION['admin'] == 1) { ?>
                                        <div class="col s1">
                                            <button type="button" class="btn red lecture_delete_file"
                                                    data-id="<?php echo $lec['data']['id'] ?>" data-file-type="1"><i
                                                        class="material-icons">delete</i></button>
                                        </div>
                                    <?php } ?>
                                    <div class="col s4 m4">
                                    <span class="grey-text">Original script by <a
                                                href="profile.php?id=<?php echo $text_user['id'] ?>"><?php echo $text_user['username'] ?></a>
                                        <?php if (strlen($lec['data']['text_link']) > 10 and $text_user['id'] != $_SESSION['id'] and $_SESSION['admin'] != 1) echo "<br> You can't change file that you have not contributed yourself. Contact its contributor for collaboration." ?>
                                    </span>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ((strlen($lec['data']['text_link']) > 10 and $text_user['id'] == $_SESSION['id']) or strlen($lec['data']['text_link']) < 3 or $_SESSION['admin'] == 1) { ?>
                                <div class="file-field input-field col s10 m8 offset-m2">
                                    <div class="btn">
                                        <span>Original script</span>
                                        <input type="file" name='lecture_script' id='lecture_script' accept=".txt">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row">
                            <?php if (strlen($lec['data']['sync_file_link']) > 10) { ?>
                                <div id="lect_sync_file_add_wrap">
                                    <div class="col s5 offset-s2">
                                        <a href="<?php echo $lec['data']['sync_file_link'] ?>" download class="btn grey"
                                           style="width: 100%"> <?php echo explode("/", $lec['data']['sync_file_link'])[2] ?></a>
                                    </div>
                                    <?php if ($sync_user['id'] == $_SESSION['id'] or $_SESSION['admin'] == 1) { ?>
                                        <div class="col s1">
                                            <button type="button" class="btn red lecture_delete_file"
                                                    data-id="<?php echo $lec['data']['id'] ?>" data-file-type="2"><i
                                                        class="material-icons">delete</i></button>
                                        </div>
                                    <?php } ?>
                                    <div class="col s4 m4">
                                    <span class="grey-text">Sync file by <a
                                                href="profile.php?id=<?php echo $sync_user['id'] ?>"><?php echo $sync_user['username'] ?></a>
                                        <?php if (strlen($lec['data']['text_link']) > 10 and $sync_user['id'] != $_SESSION['id'] and $_SESSION['admin'] != 1) echo "<br> You can't change file that you have not contributed yourself. Contact its contributor for collaboration." ?>
                                    </span>

                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ((strlen($lec['data']['sync_file_link']) > 10 and $sync_user['id'] == $_SESSION['id']) or strlen($lec['data']['sync_file_link']) < 3 or $_SESSION['admin'] == 1) { ?>
                                <div class="file-field input-field col s12 m8 offset-m2">
                                    <div class="btn">
                                        <span>Sync file</span>
                                        <input type="file" name='lecture_sync' id='lecture_sync' accept=".mbpsf">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <?php if ($lec['trans'] != NULL) {
                            foreach ($lec['trans'] as $trans) {
                                $trans_user = User::get_user_by_id($trans['contributor_id']);
                                ?>
                                <div class="row" id="trans_div_<?php echo $trans['id'] ?>">
                                    <div class="col s5 offset-s2">
                                        <a href="<?php echo $trans['trans_link'] ?>" download class="btn grey"
                                           style="width: 100%;"><?php echo explode("/", $trans['trans_link'])[2] . " (" . $trans['l_name'] . ")" ?></a>
                                    </div>
                                    <?php if ($trans_user['id'] == $_SESSION['id'] or $_SESSION['admin'] == 1) { ?>
                                        <div class="col s1">
                                            <button type="button" class="btn red trans_delete"
                                                    data-id="<?php echo $trans['id'] ?>"><i
                                                        class="material-icons">delete</i></button>
                                        </div>
                                    <?php } ?>
                                    <div class="col s4">
                                        <span class="grey-text">Parallel translation by <a
                                                    href="profile.php?id=<?php echo $trans_user['id'] ?>"><?php echo $trans_user['username'] ?></a></span>
                                    </div>
                                </div>
                                <?php
                            }
                        } ?>

                        <div id="translations">
                        </div>

                        <input hidden id="trans_count" type="number" name="trans_count" value="0">

                        <div class="row center-align">
                            <div class="col s2 offset-s5">
                                <p>Add translation</p>
                                <a class="btn-floating green btn-large" id="add_translation"><i
                                            class="material-icons right">add</i></a>
                            </div>
                        </div>
                        <div class="row center-align">
                            <div class="input-field col s6 m3 offset-m3">
                                <button type="submit" class="btn green waves-effect" name="save_lecture">Save article
                                    <i class="material-icons right">check</i></button>
                            </div>
                            <div class="input-field col s6 m3">
                                <a href="index.php" class="btn red lighten-2 waves-effect">Discard changes
                                    <i class="material-icons right">clear</i></a>
                            </div>
                        </div>

                    </div>
                </form>
            </main>
            <?php
        }

        /**
         *  prints page with admin management actions
         */
        public static function admin_page()
        {
            ?>

            <main style="margin: 3em 0">
                <div class="container">
                    <div class="row">
                        <div class="col s6 m3">
                            <div class="container blue" style="width: 100%; margin: 1em 0">
                                <a href="admin.php?mode=users" class="btn btn-large pink" style="width: 100%;">Users</a>
                            </div>
                        </div>
                        <div class="col s6 m3">
                            <div class="container blue" style="width: 100%; margin: 1em 0">
                                <a href="admin.php?mode=contributions" class="btn btn-large green" style="width: 100%;">Contributions</a>
                            </div>
                        </div>
                        <div class="col s6 m3">
                            <div class="container blue" style="width: 100%; margin: 1em 0">
                                <a href="#" class="btn btn-large grey" style="width: 100%;">Placeholder</a>
                            </div>
                        </div>
                        <div class="col s6 m3">
                            <div class="container blue" style="width: 100%; margin: 1em 0">
                                <a href="#" class="btn btn-large grey" style="width: 100%;">Placeholder</a>
                            </div>
                        </div>
                        <div class="col s6 m3">
                            <div class="container blue" style="width: 100%; margin: 1em 0">
                                <a href="#" class="btn btn-large grey" style="width: 100%;">Placeholder</a>
                            </div>
                        </div>
                        <div class="col s6 m3">
                            <div class="container blue" style="width: 100%; margin: 1em 0">
                                <a href="#" class="btn btn-large grey" style="width: 100%;">Placeholder</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php
        }

        public static function admin_contributions()
        {
        $page_entries = 5;
        $start_from = 0;
        if (isset($_GET['page'])) $start_from = ($_GET['page'] - 1) * $page_entries;

        $avail_langs = Lecture::get_avail_langs();

        if(isset($_POST['filter_sent'])){
            $_SESSION['is_filtered'] = true;
            $_SESSION['filter_language'] = $_POST['filter_language'];
            $_SESSION['filter_diff'] = $_POST['filter_diff'];
            $_SESSION['filter_order'] = $_POST['filter_order'];
        }
        elseif(isset($_POST['filter_reset']) || !isset($_GET['page'])){
            unset($_SESSION['is_filtered']);
            unset($_SESSION['filter_language']);
            unset($_SESSION['filter_diff']);
            unset($_SESSION['filter_order']);
        }

        ?>
        <main style="margin: 3em 0">
            <div class='container'>

                <form id="lecture_filter" method="post" action="index.php">
                    <div class="row">
                        <div class="input-field col s6 m3">
                            <select id="filter_language" name="filter_language" required>
                                <option selected value="Def">All</option>
                                <?php
                                foreach ($avail_langs as $alang) {
                                    if (isset($_SESSION['filter_language']) && $_SESSION['filter_language'] == $alang['id']) echo "<option value='" . $alang['id'] . "' selected>" . $alang['name'] . "</option>";
                                    else echo "<option value='" . $alang['id'] . "'>" . $alang['name'] . "</option>";
                                }
                                ?>
                            </select>
                            <label>Language</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <select id="filter_diff" name="filter_diff" required>
                                <option selected value="Def">All</option>
                                <option value="1" <?php if (isset($_SESSION['filter_diff']) && $_SESSION['filter_diff'] == 1) echo "selected" ?>>A</option>
                                <option value="2" <?php if (isset($_SESSION['filter_diff']) && $_SESSION['filter_diff'] == 2) echo "selected" ?>>B</option>
                                <option value="3" <?php if (isset($_SESSION['filter_diff']) && $_SESSION['filter_diff'] == 3) echo "selected" ?>>C</option>
                            </select>
                            <label>Difficulty</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <select id="filter_order" name="filter_order" required>
                                <option selected value="Def">Default</option>
                                <option value="1" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 1) echo "selected" ?>>(A-Z)</option>
                                <option value="2" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 2) echo "selected" ?>>(Z-A)</option>
                                <option value="3" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 3) echo "selected" ?>>Downloads(Asc)</option>
                                <option value="4" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 4) echo "selected" ?>>Downloads(Desc)</option>
                                <option value="5" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 5) echo "selected" ?>>Date(Oldest)</option>
                                <option value="6" <?php if (isset($_SESSION['filter_order']) && $_SESSION['filter_order'] == 6) echo "selected" ?>>Date(Newest)</option>
                            </select>
                            <label>Order By</label>
                        </div>
                        <div class="input-field col s6 m2">
                            <button type="submit" class="btn blue darken-4" id="filter_sent" name="filter_sent">Apply filter
                            </button>
                        </div>
                        <div class="input-field col s6 m1">
                            <button type="submit" class="btn red darken-4" id="filter_reset" name="filter_reset">Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        <?php
        $lectures_count = Lecture::get_lectures_count();

        if (isset($_SESSION['is_filtered'])) {
            $lang = $_SESSION['filter_language'];
            $diff = $_SESSION['filter_diff'];
            $ord = $_SESSION['filter_order'];
            $lectures = Lecture::get_lectures_filtered($start_from, $page_entries, $lang, $diff, $ord);
            $lectures_count = Lecture::get_lectures_filtered_count($lang, $diff, $ord);

        } else $lectures = Lecture::get_lectures($start_from, $page_entries);

        if ($lectures == null) {
            self::warning_card("No articles found.");
            return;
        }
        ?>

        <div class="container">
            <table class='responsive-table'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Article name</th>
                    <th>Article Language</th>
                    <th>Difficulty</th>
                    <th>Length</th>
                    <th>Downloads</th>
                    <th></th>
                </tr>
                </thead>
                <?php

                echo "<tbody>";
                foreach ($lectures as $lecture) {

                    $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name']);
                    $lecture_name = $lecture['data']['name'];
                    $lecture_id = $lecture['data']['id'];
                    $lecture_lang = $lecture['data']['l_name'];
                    $lecture_diff = $lecture['data']['difficulty'];
                    $lecture_down_count = $lecture['data']['download_count'];

                    echo "<tr>";
                    echo "<td>$lecture_id</td>";
                    echo "<td>$lecture_name</td>";
                    echo "<td>$lecture_lang</td>";
                    if($lecture_diff == 1) echo "<td>A</td>";
                    elseif($lecture_diff == 2) echo "<td>B</td>";
                    else echo "<td>C</td>";
                    echo "<td></td>";
                    echo "<td>$lecture_down_count x</td>";
                    echo "<td><button data-swal_id='swal_$modalname' data-swal_lec_id='$lecture_id' class='waves-effect waves-blue btn red swalbtn'><i class='fa fa-times'></i></button> <a class='waves-effect waves-blue btn blue modal-trigger' href='#$modalname'><i class='fa fa-search'></i></a></td>";
                    echo "</tr>";

                }


                echo "</tbody></table><ul class='pagination'>";
                if ( !isset($_GET['page']) || $_GET['page'] == 1) echo "<li class='disabled'><a href='#!'><i class='material-icons'>chevron_left</i></a></li>";
                else echo "<li class='waves-effect'><a href='?page=".($_GET['page']-1)."'><i class='material-icons'>chevron_left</i></a></li>";
                for ($i = 1; $i < ($lectures_count / $page_entries) + 1; $i++) {
                    if ((isset($_GET['page']) && $i == $_GET['page']) || (!isset($_GET['page']) && $i == 1)) echo "<li class='active blue'><a href='?page=$i'>$i</a></li>";
                    else echo "<li class='waves-effect'><a href='?page=$i'>$i</a></li>";
                }
                if ((isset($_GET['page']) && $_GET['page'] == ($lectures_count / $page_entries)) || (!isset($_GET['page']) && ($lectures_count / $page_entries) <= 1)) echo "<li class='disabled'><a href='#!'><i class='material-icons'>chevron_right</i></a></li>";
                elseif(!isset($_GET['page']) && ($lectures_count / $page_entries) > 1) echo "<li class='waves-effect'><a href='?page=2'><i class='material-icons'>chevron_right</i></a></li>";
                else echo "<li class='waves-effect'><a href='?page=".($_GET['page']+1)."'><i class='material-icons'>chevron_right</i></a></li>";
                echo "</ul>";
                echo "</div>";

                foreach ($lectures as $lecture) {
                    $modalname = hash('md5', $lecture['data']['id'] . $lecture['data']['name']);
                    $lecture_desc = $lecture['data']['description'];
                    $lecture_id = $lecture['data']['id'];
                    $lecture_media_link = $lecture['data']['audio_link'];
                    $lecture_text_link = $lecture['data']['text_link'];
                    $lecture_sync_link = $lecture['data']['sync_file_link'];
                    $lecture_lang = $lecture['data']['l_name'];
                    $lecture_name = $lecture['data']['name'];
                    $contributor = User::get_user_by_id($lecture['data']['user_id']);

                    if(isset($_SESSION['id'])){
                        $is_starred = Lecture::is_starred($lecture_id, $_SESSION['id']);
                    }


                    /*print "<pre>";
                    print_r($lecture);
                    print "</pre>";*/
                    ?>
                    <div class='modal' id='<?php echo $modalname ?>'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h4><?php echo $lecture_name ?></h4>
                                <?php if(isset($_SESSION['id'])){?>
                                    <?php if($_SESSION['id'] != $contributor['id']){ ?><a href="#!" class='star_btn' data-lectureid="<?php echo $lecture_id?>" data-userid="<?php echo $_SESSION['id']?>"
                                                                                          style='position: fixed; right: 5em; top: 0.5em; font-size: 2em;'><i class="material-icons" id="star_icon_<?php echo $lecture_id?>"><?php if($is_starred) echo "star" ?><?php if(!$is_starred) echo "star_border"?></i></a><?php } ?>
                                    <a href='lecture_edit.php?lid=<?php echo $lecture_id ?>' class=''
                                       style='position: fixed; right: 3em; top: 0.5em; font-size: 2em;'><i class="material-icons">edit</i></a>
                                <?php }?>
                                <a href='#' class='modal-close'
                                   style='position: fixed; right: 1em; top: 0.5em; font-size: 2em;'>&times;</a></div>

                            <p><strong>Language:</strong> <?php echo $lecture_lang ?></p>
                            <p><strong>Contributor:</strong> <a
                                        href="profile.php?id=<?php echo $contributor['id'] ?>"><?php echo $contributor['username'] ?></a>
                            </p>
                            <p><strong>Description:</strong> <?php echo $lecture_desc ?></p>
                            <hr>
                            <h5>Downloads</h5>
                            <?php if(file_exists($lecture_media_link)){ ?><p><a href='<?php echo $lecture_media_link ?>' download class='btn waves-effect waves-blue blue'>Audio File</a></p><?php }?>
                            <?php if(file_exists($lecture_text_link)){ ?><p><a href='<?php echo $lecture_text_link ?> ' download class='btn waves-effect waves-blue blue'>Original script File</a></p><?php }?>
                            <?php if(file_exists($lecture_sync_link)){ ?><p><a href='<?php echo $lecture_sync_link ?> ' download class='btn waves-effect waves-blue blue'>Sync file</a></p><?php } ?>

                            <?php
                            if ($lecture['trans'] != "") {
                                foreach ($lecture['trans'] as $tran) {
                                    $lang = $tran['l_name'];
                                    $link = $tran['trans_link'];
                                    if(file_exists($lecture_media_link)){
                                        echo "\t\t\t\t<p><a href='$link' download class='btn waves-effect waves-blue blue'>Parallel translation ( $lang ) </a></p>\n";
                                    }
                                }
                            }
                            ?>

                            <form id="download_<?php echo $modalname ?>" hidden>

                                <input type="checkbox" data-url="<?php echo $lecture_media_link ?>" checked hidden/>
                                <?php if(file_exists($lecture_text_link)){ ?><input type="checkbox" data-url="<?php echo $lecture_text_link ?>" checked hidden/><?php }?>
                                <?php if(file_exists($lecture_sync_link)){ ?><input type="checkbox" data-url="<?php echo $lecture_sync_link ?>" checked hidden/><?php }?>
                                <?php

                                if ($lecture['trans'] != "") {
                                    foreach ($lecture['trans'] as $tran) {
                                        $link = $tran['trans_link'];
                                        if(file_exists($lecture_media_link)){
                                            echo "\t\t\t\t<input type='checkbox' data-url='$link' checked hidden/>\n";
                                        }
                                    }
                                }

                                ?>
                            </form>

                            <div class='modal-footer'>

                                <div class="progress" id="progress_bar_<?php echo $modalname ?>" style="display: none">
                                    <div class="indeterminate"></div>
                                </div>
                                <p class="hide" id="result_<?php echo $modalname ?>"></p>
                                <button type="submit" form="download_<?php echo $modalname ?>"
                                        class="download_lecture btn green waves-effect"
                                        data-lecture-id="<?php echo $lecture_id ?>"
                                        data-lecture-name="<?php echo $lecture_name ?>" data-lecture="<?php echo $modalname ?>">
                                    Download all
                                </button>
                                <a href='#' class='modal-close waves-effect waves-blue btn blue'>Close</a>

                            </div>
                        </div>
                    </div>
                    </main>

                    <?php
                }

                }


        /**
         *  prints list of all users for admin to manage
         */
        public static function admin_users()
        {
            ?>
            <main style="margin: 3em 0">
                <div class="container">
                    <table>
                        <thead>

                        </thead>
                        <tbody>
                        <?php

                        ?>

                        <?php
                        ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php
        }

        }

        ?>
