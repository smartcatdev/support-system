<?php
/**
 * Template for e-commerce section in the single ticket sidebar.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>

<!-- panel-body -->

    <div class="product-info">

        <span class="lead"><?php esc_html_e( $product ); ?>

    </div>

    <?php if( !empty( $receipt_id ) ) : ?>

        <div class="purchase-info">

            <span><?php printf( __( "Receipt # %s", 'ucare' ), $receipt_id ); ?></span>

        </div>

    <?php endif; ?>

<!-- /panel-body -->