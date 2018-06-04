<?php
/**
 * Template for the login page.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

?>

<?php ucare_get_header(); ?>

<div id="support-login-bg" xmlns="http://www.w3.org/1999/html">

    <div id="support-login-page">
        <div id="support-login-wrapper"><?php login_form(); ?></div>
    </div>

</div>

<?php ucare_get_footer(); ?>
