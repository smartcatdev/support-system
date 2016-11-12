jQuery(document).ready(function ($) {
    var SmartcatSupport = window.SmartcatSupport;

    var CommentEvents = {

        initialize: function() {
            $(document).on('click', '.comment .action', function() {
                CommentActions[$(this).data('action')]($(this));
            });
        }

    };

    var CommentActions = {
        edit_comment: function( context ) {
            var comment_id = context.parents('.comment').data('id');

            SmartcatSupport.wp_ajax('support_comment_edit', {comment_id: comment_id}, function(response) {
                context.parents('.comment').find('.content').html(response.data);
                SmartcatSupport.tinyMCE('[name="comment_content"]');
            })
        }
    };


    (function(){
        CommentEvents.initialize();
    })();
});