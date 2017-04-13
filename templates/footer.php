<?php

use SmartcatSupport\descriptor\Option;

$ver = get_option( Option::PLUGIN_VERSION );

?>

<footer id="footer">

    <div class="container">

        <hr>

        <p class="footer-text text-center">

            <?php $footer_text = get_option( Option::FOOTER_TEXT, Option\Defaults::FOOTER_TEXT ); ?>

            <?php echo !empty( $footer_text ) ? $footer_text . ' |' : ''; ?>

            <a href="http://ucaresupport.com" target="_blank">

                <?php _e( ' Powered by uCare Support', \SmartcatSupport\PLUGIN_ID ); ?>

            </a>

        </p>

    </div>

</footer>

<script>

    var Globals = {
        ajax_url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
        ajax_nonce: "<?php echo wp_create_nonce( 'support_ajax' ); ?>",
        strings: {
            loading_tickets: "Loading Tickets...",
            loading_generic: "Loading..."
        }
    };

</script>

<script type="text/template" class="ajax-loader-mask">

    <div class="ajax-loader">

        <div class="dot-container">

            <div class="dot dot-1"></div>

        </div>

        <div class="dot-container">

            <div class="dot dot-2"></div>

        </div>

        <div class="dot-container">

            <div class="dot dot-3"></div>

        </div>

        <p class="text-center"><%= obj %></p>

    </div>

</script>

<script type="text/template" class="notice-inline">

    <div style="border-radius: 0; margin: 0" class="alert alert-success fade in">

        <a href="#" class="close" data-dismiss="alert">×</a><%= obj %>

    </div>

</script>

<script src="<?php echo home_url( 'wp-includes/js/underscore.min.js' ) . '?ver=' . $ver; ?>"></script>
<script src="<?php echo $url . '/assets/lib/bootstrap/js/bootstrap.min.js' . '?ver=' . $ver; ?>"></script>
<script src="<?php echo $url . 'assets/lib/scrollingTabs/scrollingTabs.min.js' . '?ver=' . $ver; ?>"></script>
<script src="<?php echo $url . 'assets/lib/dropzone/js/dropzone.min.js' . '?ver=' . $ver; ?>"></script>
<script src="<?php echo $url . 'assets/lib/lightGallery/js/lightgallery.min.js' . '?ver=' . $ver; ?>"></script>
<script src="<?php echo $url . 'assets/lib/lightGallery/plugins/lg-zoom.min.js' . '?ver=' . $ver; ?>"></script>
<script src="<?php echo $url . 'assets/lib/moment/moment.min.js' . '?ver=' . $ver ?>" ></script>
<script src="<?php echo $url . 'assets/js/plugins.js' . '?ver=' . $ver ?>" ></script>
<script src="<?php echo $url . 'assets/js/app.js' . '?ver=' . $ver ?>" ></script>
<script src="<?php echo $url . 'assets/js/settings.js' . '?ver=' . $ver ?>" ></script>
<script src="<?php echo $url . 'assets/js/ticket.js' . '?ver=' . $ver ?>" ></script>
<script src="<?php echo $url . 'assets/js/comment.js' . '?ver=' . $ver ?>" ></script>

<script>

    Dropzone.prototype.defaultOptions.maxFilesize = <?php echo get_option( Option::MAX_ATTACHMENT_SIZE, Option\Defaults::MAX_ATTACHMENT_SIZE ); ?>;

</script>

</body>

</html>