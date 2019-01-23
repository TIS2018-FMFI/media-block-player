<?php

/**
 * @author Martin Hrebeňár
 */

session_start();

include_once ('user.php');
include_once ('page.php');

Page::page_header('Register');
Page::page_navbar();

//print_r($_POST);

if (isset($_POST['username']) &&
    isset($_POST['g-recaptcha-response']) && strlen($_POST['g-recaptcha-response']) > 0 &&
    isset($_POST['password']) &&
    isset($_POST['password2']) &&
    isset($_POST['email']) &&
    strcmp($_POST['password'], $_POST['password2']) == 0) {
    User::add_user($_POST['username'], $_POST['password'], $_POST['email']);
    $_SESSION['msg'] = "Registration successful. You can now log in.";
    $_SESSION['msg_status'] = "OK";
    header("location: login.php?reg=ok");
}

?>

    <main style="margin: 3em 0">
        <div class="container"">
            <div class="row">
                <form id="register" class="col s6 offset-s4" method="post">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="username" type="text" class="validate" data-error="#errorTxt1" name="username" required value="<?php if(isset($_POST['username'])) echo $_POST['username']?>">
                            <label for="username">Username</label>
                            <div class="f_error" id="errorTxt1"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <input id="email" type="email" class="validate" data-error="#errorTxt2" name="email" required value="<?php if(isset($_POST['email'])) echo $_POST['email']?>">
                            <label for="email">E-mail address</label>
                            <div class="f_error" id="errorTxt2"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <label for="password">Password</label>
                            <input id="password" type="password" data-error="#errorTxt3" class="validate" name="password" required>
                            <div class="f_error" id="errorTxt3"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <label for="password2">Repeat Password</label>
                            <input id="password2" type="password" class="validate" data-error="#errorTxt4" name="password2" required>
                            <div class="f_error" id="errorTxt4"></div>
                        </div>
                    </div>

                    <!-- reCaptcha for server -->
                    <!-- <div class="g-recaptcha" data-sitekey="6LeN9X8UAAAAAM0AqDW5mzD2lFErojgDUwHoR1Bk"></div>
<div class="g-recaptcha" id="captcha" data-sitekey="6LeS9X8UAAAAAEwiV4nZW9aVOTv4Wgl96EZVD5eY"></div>-->

                    <!-- reCaptcha for localhost -->
                    <div class="row">
                        <div class="input-field col s6">
                            <span class="msg-error error"></span>
                            <div class="g-recaptcha" data-sitekey="6LeN9X8UAAAAAM0AqDW5mzD2lFErojgDUwHoR1Bk"></div>
                            <input type="checkbox" name="cap" id="cap" hidden required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <button type="submit" class="btn blue waves-effect" name="action">Register
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
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

        if(jQuery){console.log("JQ OK");}else console.log("JQ NotOK");

        $().ready(function () {


            $('#cap').click(function () {
                console.log('CLick');
            });


            $('#register').validate({
                rules: {
                    username:{
                        required: true,
                        minlength: 3,
                    },
                    email:{
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password2: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password",
                    },
                },
                messages: {
                    username: {
                        required: 'Please enter username.',
                        minlength: 'Your username must be at least 3 characters long.'
                    },
                    email:{
                        required: 'Please enter your email.',
                        email: 'Entered email is not valid',
                    },
                    password: {
                        required: 'Please enter password.',
                        minlength: 'Your password must be at least 5 characters long.'
                    },
                    password2: {
                        required: 'Please enter your password again.',
                        minlength: 'Your password must be at least 5 characters long.',
                        equalTo: 'Your passwords are not the same.'
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
