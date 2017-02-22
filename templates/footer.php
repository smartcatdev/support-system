<?php

use SmartcatSupport\descriptor\Option;

?>

    <script>

        var Globals = {
            ajax_url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            ajax_nonce: "<?php echo wp_create_nonce( 'support_ajax' ); ?>"
        };

    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <script src="<?php echo home_url( 'wp-includes/js/underscore.min.js' ); ?>"></script>
    <script src="<?php echo $url . 'assets/lib/datatables/datatables.min.js' ?>" ></script>
<!--    <script src="--><?php //echo $url . 'assets/lib/modal/jquery.modal.min.js' ?><!--" ></script>-->
<!--    <script src="--><?php //echo includes_url( 'js/tinymce/' ) . 'wp-tinymce.php' ?><!--" ></script>-->

    <script src="<?php echo $url . 'assets/js/plugins.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/js/app.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/js/settings.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/js/ticket.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/js/comment.js' ?>" ></script>
    </body>
</html>
