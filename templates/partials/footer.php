<?php

namespace ucare;

$ver = get_option( Options::PLUGIN_VERSION );

$url = resolve_url();

?>
        </div><!-- End Page Container -->

        <footer id="footer">

            <div class="container">

                <p class="footer-text text-center">

                    <?php echo get_option( Options::FOOTER_TEXT, Defaults::FOOTER_TEXT ) . ' | ' ?: ''; ?>

                    <a href="http://ucaresupport.com" target="_blank">
                        <?php _e( 'Powered by uCare Support', 'ucare' ); ?>
                    </a>

                </p>

            </div>

        </footer>

        <?php print_footer_scripts() ?>

        <script>

            var Globals = {
                ajax_url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                ajax_nonce: "<?php echo wp_create_nonce( 'support_ajax' ); ?>",
                refresh_interval: <?php echo get_option( Options::REFRESH_INTERVAL, Defaults::REFRESH_INTERVAL ); ?>,
                strings: {
                    loading_tickets: "<?php _e( "Loading Tickets...", 'ucare' ); ?>",
                    loading_generic: "<?php _e( "Loading...", 'ucare' ); ?>",
                    delete_comment: "<?php _e( "Delete Comment", 'ucare' ); ?>",
                    delete_attachment: "<?php _e( "Delete Attachment", 'ucare' ); ?>",
                    close_ticket: "<?php _e( "Close Ticket", 'ucare' ); ?>",
                    warning_permanent: "<?php _e( "Are you sure you want to do this? This operation cannot be undone!", 'ucare' ); ?>",
                    yes: "<?php _e( "Yes", 'ucare' ); ?>",
                    cancel: "<?php _e( "Cancel", 'ucare' ); ?>"
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

        <script type="text/template" class="confirm-modal">

            <div id="<%= id %>" class="modal close-ticket-modal fade">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                            <h4 class="modal-title"><%= title %></h4>

                        </div>

                        <div class="modal-body">

                            <p><%= content %></p>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="button confirm">

                                <span class="glyphicon glyphicon-ok button-icon"></span>

                                <span><%= okay_text %></span>

                            </button>

                            <button type="button" class="button button-submit cancel"
                                    data-target="#<%= id %>"
                                    data-toggle="modal">

                                <span class="glyphicon glyphicon-ban-circle button-icon"></span>

                                <span><%= cancel_text %></span>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </script>


        <script src="<?php echo $url . 'assets/js/plugins.js' . '?ver=' . $ver ?>" ></script>
        <script src="<?php echo $url . 'assets/js/app.js' . '?ver=' . $ver ?>" ></script>
        <script src="<?php echo $url . 'assets/js/settings.js' . '?ver=' . $ver ?>" ></script>
        <script src="<?php echo $url . 'assets/js/ticket.js' . '?ver=' . $ver ?>" ></script>
        <script src="<?php echo $url . 'assets/js/comment.js' . '?ver=' . $ver ?>" ></script>

        <script>

            Dropzone.prototype.defaultOptions.maxFilesize = <?php echo get_option( Options::MAX_ATTACHMENT_SIZE, Defaults::MAX_ATTACHMENT_SIZE ); ?>;

        </script>

        <?php do_action( 'ucare_footer' ); ?>

    </body><!-- End body -->

</html><!-- End html -->
