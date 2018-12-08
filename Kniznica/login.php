<?php

session_start();

include_once ('user.php');
include_once ('page.php');

Page::page_header('Login');
Page::page_navbar();

if (isset($_POST['username']) && isset($_POST['password'])){
    User::check_user($_POST['username'],  $_POST['password']);
}

?>
<main>
    <div class="container" style="margin-top: 10em">
        <div class="row">
            <form id="login_form" class="col s6 offset-s4" method="post">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="username" type="text" class="validate" name="username">
                        <label for="username">Username / Email</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6">
                        <input id="password" type="password" class="validate" name="password">
                        <label for="password">Password</label>
                    </div>
                </div>

                <div class="row">
                    <button type="submit" class="btn blue waves-effect" name="action">Login
                        <i class="material-icons right">send</i></button>
                </div>
            </form>
        </div>
    </div>

</main>
<?php

Page::page_footer();

?>
