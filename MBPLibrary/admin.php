<?php

/**
 * @author Martin Hrebeňár
 */

date_default_timezone_set('UTC');

session_start();

include_once('page.php');
include_once ('user.php');

Page::page_header('Admin Zone');
Page::page_navbar();

if($_SESSION['admin'] != 1){
    Page::error_card('You do not have permission to access this page.');
}
else {
    if(isset($_GET['mode'])){
        Page::warning_card("NYI");
    }
    else{
        Page::admin_page();
    }
}

?>


<?php
Page::page_footer();
Page::page_foot();
?>
