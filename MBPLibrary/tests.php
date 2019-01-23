<?php
include_once ('user.php');
include_once ('lecture.php');
include_once ('page.php');

Page::page_header('TESTS');
?>

    <h2>Test 1 - login_successful</h2>
    <p>Desired result: true</p>
    <p>Actual result: <b><?php echo User::check_user('admin', 'admin', 1) ? 'true' : 'false' ?></b></p>

    <h2>Test 2 - login_unsuccessful</h2>
    <p>Desired result: false</p>
    <p>Actual result: <b><?php echo User::check_user('admis', 'admin', 1) ? 'true' : 'false' ?></b></p>

    <h2>Test 3 - login_with_email</h2>
    <p>Desired result: true</p>
    <p>Actual result: <b><?php echo User::check_user('admin@admin.com', 'admin', 1) ? 'true' : 'false' ?></b></p>

    <h2>Test 4 - get_user_correct_id</h2>
    <p>Desired result: (username) admin</p>
    <p>Actual result: <b><?php $u = User::get_user_by_id(1); echo $u['username'] ?></b></p>

    <h2>Test 5 - get_user_incorrect_id</h2>
    <p>Desired result: NULL</p>
    <p>Actual result: <b><?php echo !User::get_user_by_id(0) ? 'NULL' : 'Object' ?></b></p>

    <h2>Test 6 - get_lectures_count</h2>
    <p>Desired result: >= 0</p>
    <p>Actual result: <b><?php echo Lecture::get_lectures_count() ?></b></p>

    <h2>Test 7 - get_languages</h2>
    <p>Desired result: Array</p>
    <p>Actual result: <b><?php echo !Lecture::get_languages() ? 'NULL' : 'Array' ?></b></p>

    <h2>Test 8 - get_lectures</h2>
    <p>Desired result: Array</p>
    <p>Actual result: <b><?php echo !Lecture::get_lectures(0,5) ? 'NULL' : 'Array' ?></b></p>

    <h2>Test 9 - get_lecture_translations_correct_lecture_id</h2>
    <p>Desired result: Array</p>
    <p>Actual result: <b><?php echo !Lecture::get_lecture_translations(1) ? 'NULL' :  'Array' ?></b></p>

    <h2>Test 10 - get_lecture_translations_incorrect_lecture_id</h2>
    <p>Desired result: NULL</p>
    <p>Actual result: <b><?php echo !Lecture::get_lecture_translations(0) ? 'NULL' :  'Array' ?></b></p>

<?php
Page::page_footer();
Page::page_foot();
?>