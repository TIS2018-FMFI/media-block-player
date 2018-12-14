<?php

session_start();

include_once ('user.php');
include_once ('page.php');
include_once ('functions.php');

Page::page_header('New lecture');
Page::page_navbar();

if(isset($_POST['save_lecture'])){
    //echo "<pre>";
    //var_dump($_POST);
    //var_dump($_FILES);
    //echo "</pre>";

    if(!check_files($_FILES, $_POST['trans_count'])) echo "ERROR FILES";
    else{

        $lec_id = Lecture::save_lecture($_POST, $_SESSION['id']);
        Lecture::save_lecture_files($_FILES, $_POST['lecture_title'], $lec_id);
        Lecture::save_lecture_translations($_FILES, $_POST,$lec_id);

        unset($_POST);
    }

}


Page::lecture_add();

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
                                    $("<span>Translation</span>")
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
