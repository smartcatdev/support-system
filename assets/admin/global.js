jQuery(document).ready( function ($) {
    "use strict";

     function init_deactivate_prompt(e) {
        e.preventDefault();

        var link = $(e.target).prop('href');
        var modal = $('#deactivate-feedback');

        modal.toggleClass('active');
        modal.find('.deactivate-url').prop('href', link);
        modal.find('form').prop('action', link);
    }

    function close_feedback(e) {
        e.preventDefault();
        $(e.target).parents('.support-admin-modal').toggleClass('active');
    }

    function toggle_feedback_reason(e) {
        var li = $(e.target).parents('li');
        var modal = $(e.target).parents('.support-admin-modal');
        var placeholder = li.data('placeholder');
        var type = li.data('type');

        modal.find('.reason-input').remove();

        var field = '<div class="reason-input">';

        if (type === 'text') {
            field += '<input type="text" name="details" placeholder="' + placeholder + '"/>';
        } else if (type === 'textarea') {
            field += '<textarea rows="5" maxlength="250" name="details" placeholder="' + placeholder + '"></textarea>';
        }

        field += '</div>';

        li.append(field);
    }

    $("#feedback-prompt a").click(init_deactivate_prompt);
    $("#close-feedback").click(close_feedback);
    $(".feedback-reason").click(close_feedback);
    $('input[name=reason]').change(toggle_feedback_reason);

});

