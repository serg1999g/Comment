$(document).ready(function () {
    let level = '',
        back = $('.back');

    $('#comment_form').on('submit', function (event) {
        event.preventDefault();
        let form_data = $(this).serialize();

        $.ajax({
            url: "add_comment.php",
            method: "POST",
            data: form_data,
            success: function (data) {
                let result = ($.parseJSON(data));
                $(".status").html(result.message);
                load_last_comment();
                text = document.getElementById('comment_content');
                text.value = '';
                $('#display_comment').before($('#comment_form'));
                $('#parent_id').val("0"); // reset parent_id
                back[0].classList.add('hide');
            }
        });
    });

    load_comment();

    function load_comment() {
        $.ajax({
            url: "fetch_comment.php",
            method: "POST",
            success: function (data) {
                let exists = true,
                    result = ($.parseJSON(data));
                // проверяем наличие комантариев
                try {
                    if (result.comment)
                        exists = true;
                } catch (e) {
                    exists = false;
                }
                if (exists) {
                    $('#display_comment').html(result.comment);
                }
                // проверяем наличие вложенных комантариев
                try {
                    if (result.script.length)
                        exists = true;
                } catch (e) {
                    exists = false;
                }
                if (exists) {
                    for (let index = 0; index < result.script.length; index++) {
                        $(".id-" + result.script[index].parent_id).after($(".id-" + result.script[index].id));
                    }
                }
            }
        });
    }

    function load_last_comment() {
        $.ajax({
            url: "load_last_comment.php",
            method: "POST",
            data: {
                level: level
            },
            success: function (data) {
                let result = ($.parseJSON(data));
                $('#display_comment').append(result.comment);
                $(".id-" + result.script[0].parent_id).after($(".id-" + result.script[0].id));
                level = "level-0";
            }
        });
    }

    $(document).on('click', '.reply', function () {
        let comment_id = $(this).attr("id");
        parentID = $(this).attr("id");
        level = $(this);
        level = level.parent().parent().parent().parent();
        level = level[0].classList[2];
        $('#parent_id').val(comment_id);

        $('#' + comment_id).parent().append($('#comment_form')); // добавление формы под дочерний комент
        back[0].classList.remove('hide');
        $('#comment_name').focus();
    });


    // кнопка возврата к добавлению родительских комментариев
    $(document).on('click', '.back', function () {
        $('#display_comment').before($('#comment_form'));
        back[0].classList.add('hide');
        $('#parent_id').val("0"); // reset parent_id
        level = "level-0";
    });

});