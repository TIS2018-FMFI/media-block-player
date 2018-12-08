<?php
date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once ('user.php');

Page::page_header('Home');
Page::page_navbar();

//print_r($_SESSION);
?>
<main>

    <div class="jumbotron center-align" style="padding: 5em 0 3em 0;">
        <h1 style="margin:0">Media Block Player: Shared database</h1>
    </div>

    <div class='container' style="margin-bottom: 2em">
        <div class='center-align'>
            <p>
            <h5>This is a shared database of knowledge compatible with our online training application that you can find: HERE</h5>
            </p>
        </div>
    </div>

    <?php
    Page::list_lectures();
    ?>

</main>
<?php
Page::page_footer();
?>
