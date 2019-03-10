<?php

/**
 * @author Martin Hrebeňár
 */

session_start();

include_once('user.php');
include_once('page.php');
include_once('functions.php');

Page::page_header('New article');
Page::page_navbar();

if (isset($_POST['save_lecture'])) {

    Lecture::update_lecture($_POST);
    Lecture::update_lecture_files($_FILES, $_POST['lid']);
    Lecture::save_lecture_translations($_FILES, $_POST, $_POST['lid']);

    unset($_POST);
    header('location: index.php');


}

if (!isset($_SESSION['id'])) {
    Page::error_card("You must be logged in to be able to add new article.");
} else {
    if (isset($_SESSION['msg'])) {
        Page::page_message($_SESSION['msg_status'], $_SESSION['msg']);
        unset($_SESSION['msg']);
        unset($_SESSION['msg_status']);
    }
    Page::lecture_edit($_GET['lid']);
}

Page::page_footer();
?>

<!-- Place for custom or page related scripts -->
<script>

    $('.trans_delete').click(function () {
        var trid = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this article!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: "./ajax_functions.php",
                        data: {
                            action: "delete_translation_file",
                            trans_id: trid,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status === "OK") {
                                $('#trans_div_'+trid).hide(function () {
                                    $(this).animate();
                                });
                            }
                        }
                    })
                }
            });
    });

    $('.lecture_delete_file').click(function () {
        var lid = $(this).data('id');
        var type = $(this).data('file-type');
        console.log(lid, type);
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this article!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: "./ajax_functions.php",
                        data: {
                            action: "delete_lecture_file",
                            lec_id: lid,
                            file_type: type,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status === "OK") {
                                if(data.type == 1){
                                    $('#lect_script_file_add_wrap').hide(function () {
                                        $(this).animate();
                                    })
                                }
                                else if(data.type == 2){
                                    $('#lect_sync_file_add_wrap').hide(function () {
                                        $(this).animate();
                                    })
                                }
                            }
                        }
                    })
                }
            });
    });

    $(document).ready(function () {
        var $trans_index = 1;

        $("#add_translation").click(function () {
            //console.log("klik ", $trans_index);

            $("#trans_count").val($trans_index);
            var $el = $("<div class='row' style='display: none'></div>")
                .attr('id', 'lecture_trans_row_' + $trans_index)
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
                                        .attr('name', 'lecture_trans_' + $trans_index)
                                        .attr('id', 'lecture_trans_' + $trans_index)
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

            $el.find('select').prop('id', 'trans_lang_' + $trans_index);
            $el.find('select').prop('name', 'trans_lang_' + $trans_index);
            $('#trans_lang_' + $trans_index + ' option').each(function () {
                $(this).prop('disabled', false);
            });
            $('select').formSelect();
            $('#lecture_trans_row_' + $trans_index).show(function () {
                $(this).animate();
            });
            $trans_index++;
        });

        $('select').formSelect();

    });
</script>

<?php
Page::page_foot();
?>
