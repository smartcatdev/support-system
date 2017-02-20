<?php

use SmartcatSupport\descriptor\Option;

?>

    <script>

        var Globals = {
            ajaxUrl: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            strings: <?php echo json_encode( array(
                'register_form_toggle' => __( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ),
            ) ); ?>
        };

    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo $url . 'assets/lib/datatables/datatables.min.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/lib/modal/jquery.modal.min.js' ?>" ></script>
<!--    <script src="--><?php //echo includes_url( 'js/tinymce/' ) . 'wp-tinymce.php' ?><!--" ></script>-->

    <script src="<?php echo $url . 'assets/js/app.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/js/ticket.js' ?>" ></script>
    <script src="<?php echo $url . 'assets/js/comment.js' ?>" ></script>
    </body>
</html>
