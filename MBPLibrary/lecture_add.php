<?php

/**
 * @author Martin Hrebeňár
 */

session_start();

include_once ('user.php');
include_once ('page.php');
include_once ('functions.php');

Page::page_header('New article');
Page::page_navbar();

if(isset($_POST['save_lecture'])){

    if(!check_files($_FILES, $_POST['trans_count'])){
        $_SESSION['msg'] = "Files cannot be uploaded.\nMax file size is 30 Mb.";
        $_SESSION['msg_status'] = "ERR";
    }
    else{
        if (!file_exists('Data/Media/')) {
            mkdir('Data/Media/', 0777, true);
        }
        if (!file_exists('Data/Scripts/')) {
            mkdir('Data/Scripts/', 0777, true);
        }
        if (!file_exists('Data/Syncs/')) {
            mkdir('Data/Syncs/', 0777, true);
        }
        if (!file_exists('Data/Translations/')) {
            mkdir('Data/Translations/', 0777, true);
        }
        $lec_id = Lecture::save_lecture($_POST, $_SESSION['id']);
        Lecture::save_lecture_files($_FILES, $_POST['lecture_title'], $lec_id);
        Lecture::save_lecture_translations($_FILES, $_POST,$lec_id);

        unset($_POST);
        header('location: index.php');
    }

}

if(!isset($_SESSION['id'])){
    Page::error_card("You must be logged in to be able to add new articlee.");
}else {
  if (isset($_SESSION['msg'])) {
      Page::page_message($_SESSION['msg_status'], $_SESSION['msg']);
      unset($_SESSION['msg']);
      unset($_SESSION['msg_status']);
  }
    Page::lecture_add();
}

Page::page_footer();
?>

<!-- Place for custom or page related scripts -->
<script>
    $(document).ready(function(){
        var $trans_index = 1;

        $("#add_translation").click(function () {
            //console.log("klik ", $trans_index);

            $("#trans_count").val($trans_index);
            var $el = $("<div class='row'></div>")
                .attr('id', 'lecture_trans_row_'+$trans_index)
                .append(
                    $("<div class='file-field input-field col s10 m6 offset-m2'></div>")
                        .append(
                            $("<div class='btn'></div>")
                                .append(
                                    $("<span>Parallel translation</span>")
                                )
                                .append(
                                    $("<input>")
                                        .attr('type', 'file')
                                        .attr('name', 'lecture_trans_'+$trans_index)
                                        .attr('id', 'lecture_trans_'+$trans_index)
                                        .attr('accept', '.txt')
                                )
                        )
                        .append(
                            $("<div class='file-path-wrapper'></div>")
                                .append(
                                    $("<input class='file-path validate' type='text'>")
                                )
                        )
                )
                .append(
                    $("<div class='input-field col s2'></div>")
                        .append(
                            $('#lecture_lang').clone()
                        )
                );

            $('#translations').append($el);

            $el.find('select').prop('id', 'trans_lang_'+$trans_index);
            $el.find('select').prop('name', 'trans_lang_'+$trans_index);
            $('select').formSelect();
            $trans_index++;
        });

        $('select').formSelect();

    });
</script>

<?php
Page::page_foot();
?>
