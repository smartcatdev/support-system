<?php
/**
 * Application footer template.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;

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

        <?php print_underscore_templates(); ?>

        <?php print_footer_scripts() ?>

        <?php do_action( 'ucare_footer' ); ?>

    </body><!-- End body -->

</html><!-- End html -->
