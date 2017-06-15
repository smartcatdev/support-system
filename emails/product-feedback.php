<?php ob_start(); ?>

    <div>

        <h2><?php _e( 'Reason', 'ucare' ); ?></h2>
        <p style="margin-left: 20px"><?php echo esc_attr( $_POST['reason'] ); ?></p>

        <?php if( !empty( $_POST['details'] ) ) : ?>

            <h2><?php _e( 'Details', 'ucare' ); ?></h2>
            <p style="margin-left: 20px"><?php echo esc_attr( $_POST['details'] ); ?></p>

        <?php endif; ?>

        <?php if( !empty( $_POST['comments'] ) ) : ?>

            <h2><?php _e( 'Comments', 'ucare' ); ?></h2>
            <p style="margin-left: 20px"><?php echo esc_attr( $_POST['comments'] ); ?></p>

        <?php endif; ?>

        <a href="<?php echo esc_url( home_url() ); ?>"><?php _e( 'website can be viewed here', 'ucare' ); ?></a>

    </div>

<?php return ob_get_clean(); ?>