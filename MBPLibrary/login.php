<?php

/**
 * @author Martin Hrebeňár
 */

session_start();

include_once ('user.php');
include_once ('page.php');

Page::page_header('Login');
Page::page_navbar();

if (isset($_POST['username']) && isset($_POST['password'])){
    if(!User::check_user($_POST['username'],  $_POST['password'])) {
        $_SESSION['msg'] = "Your login credentials are invalid.";
        $_SESSION['msg_status'] = "ERR";
    }
}

?>

<main>

    <?php
    if(isset($_SESSION['msg'])){
        Page::page_message($_SESSION['msg_status'], $_SESSION['msg']);
        unset($_SESSION['msg']);
        unset($_SESSION['msg_status']);
    }
    ?>

    <div class="container" style="margin-top: 10em; margin-bottom: 10em">
        <div class="row">
            <form id="login" class="col s6 offset-s4" method="post">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="username" type="text" class="validate" data-error="#errorTxt1" name="username" required>
                        <label for="username">Username / Email</label>
                        <div class="f_error" id="errorTxt1"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6">
                        <input id="password" type="password" class="validate" data-error="#errorTxt2" name="password" required>
                        <label for="password">Password</label>
                        <div class="f_error" id="errorTxt2"></div>
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

<!-- Place for custom or page related scripts -->
<script>
    $().ready(function () {
        $('#login').validate({
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                },
                password: {
                    required: true,
                    minlength: 5,
                },
            },
            messages: {
                username: {
                    required: 'Please enter your username or email',
                    minlength: 'Your username must be at least 3 characters long',
                },
                password: {
                    required: 'Please enter your password',
                    minlength: 'Your password must be at least 5 characters long',
                },
            },
            errorElement : 'div',
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                    console.log(error);
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        })
    })
</script>

<?php
Page::page_foot();
?>
