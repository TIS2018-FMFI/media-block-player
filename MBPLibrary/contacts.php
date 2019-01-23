<?php

/**
 * @author Martin Hrebeňár
 */

date_default_timezone_set('UTC');

session_start();

include_once('page.php');

Page::page_header('Contact');
Page::page_navbar();

Page::warning_card('NYI');

?>



<?php
Page::page_footer();
Page::page_foot();
?>