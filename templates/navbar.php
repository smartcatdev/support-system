<?php
$user = wp_get_current_user();
?>
<div id="navbar" class="background-secondary">
    <div class="container">
        <div class="row">
            <?php if ( !empty( $user ) ) : ?>
                <div class="col-sm-6 alignleft row-table">
                    <div class="row-table-cell">

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
                        <div class="dropdown-wrapper">
                            <a href="#" 
                               class="dropdown-toggle" 
                               data-toggle="dropdown" 
                               role="button" 
                               aria-haspopup="true" 
                               aria-expanded="false"><?php echo $user->data->display_name; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">

                                <li>
                                    <a href="#" class="alignright background-secondary hover menu-item">
                                        <span class="glyphicon glyphicon-cog"></span>
                                        <?php _e( 'Settings', SmartcatSupport\PLUGIN_ID ); ?>
                                    </a>    
                                </li>

                                <li role="separator" class="divider"></li>

                                <li>
                                    <a href="<?php echo wp_logout_url(); ?>" class="alignright background-secondary hover menu-item">
                                        <span class="glyphicon-log-out glyphicon"></span>
                                        <?php _e( 'Logout', SmartcatSupport\PLUGIN_ID ); ?>
                                    </a>    
                                </li>
                            </ul>

                        </div>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
