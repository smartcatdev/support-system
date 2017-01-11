<?php
$user = wp_get_current_user();

?>
<div id="navbar" class="background-secondary">
    <div class="container">
        <div class="row">
            <?php if ( !empty( $user ) ) : ?>
                <div class="col-sm-6 alignleft row-table">
                    <div class="row-table-cell">

                        <a href="#name" class="background-secondary hover menu-item">
                            <span class="glyphicon-user glyphicon"></span>
                            <?php echo $user->data->display_name; ?>
                        </a>
                        <a href="#date" class="background-secondary hover menu-item">
                            <span class="glyphicon-calendar glyphicon"></span>
                            <?php echo current_time( 'M d' ); ?>
                        </a>
                        <a href="#time" class="background-secondary hover menu-item">
                            <span class="glyphicon-time glyphicon"></span>
                            <span class="current-time"><?php echo current_time( 'H:i a' ); ?></span>
                        </a>


                    </div>
                </div>
                <div class="col-sm-6 alignright row-table">
                    <div class="row-table-cell">
                        <a href="<?php echo wp_logout_url(); ?>" class="alignright background-secondary hover menu-item">
                            <span class="glyphicon-log-out glyphicon"></span>
                            <?php _e( 'Logout' ); ?>
                        </a>          
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
