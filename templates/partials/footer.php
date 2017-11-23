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

                    <?php print_footer_copyright(); ?>

                </p>

            </div>

        </footer>

        <?php print_underscore_templates(); ?>

        <?php print_footer_scripts() ?>

        <?php do_action( 'ucare_footer' ); ?>

    </body><!-- End body -->

</html><!-- End html -->
