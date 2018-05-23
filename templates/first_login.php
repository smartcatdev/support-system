<?php namespace ucare;

$user = wp_get_current_user();

?>

<div class="modal fade" tabindex="-1" role="dialog" id="welcome-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong><?php echo __( 'Welcome ', 'ucare' ) . $user->user_firstname ?>!</strong></h4>
            </div>
            
            <div class="modal-body">
                

                <?php if ( is_array( $user->roles ) && ( in_array( 'support_user', $user->roles ) || user_can( $user, 'manage_support_tickets' ) ) ) : ?>
                
                    <p><?php _e( 'Since this is your first login to the system, we will take a view seconds to quickly show you around.', 'ucare' ); ?></p>
                    <br>

                    <script src="<?php esc_url_e( resolve_url( '/assets/js/first_login_agent.js' ) ); ?>"></script>

                    <div id="carousel-example-generic" class="carousel" >
                      <!-- Indicators -->
                      <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                      </ol>

                      <!-- Wrapper for slides -->
                      <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img src="<?php echo \ucare\Plugin::plugin_url( \ucare\PLUGIN_ID ); ?>assets/images/slide-1.jpg" alt="<?php _e( 'Creating tickets', 'ucare' ); ?>">
                          <div class="carousel-caption">
                            <?php _e( 'Click on the "Create Ticket" button to create a support request', 'ucare' ); ?>
                          </div>
                        </div>
                        <div class="item">
                          <img src="<?php echo \ucare\Plugin::plugin_url( \ucare\PLUGIN_ID ); ?>assets/images/slide-2.jpg" alt="<?php _e( 'Creating tickets', 'ucare' ); ?>">
                          <div class="carousel-caption">
                            <?php _e( 'Your tickets will be displayed in the ticket view once created.', 'ucare' ); ?>
                          </div>
                        </div>
                        <div class="item">
                          <img src="<?php echo \ucare\Plugin::plugin_url( \ucare\PLUGIN_ID ); ?>assets/images/slide-3.jpg" alt="<?php _e( 'Creating tickets', 'ucare' ); ?>">
                          <div class="carousel-caption">
                            <?php _e( 'You can update your password from the Settings menu', 'ucare' ); ?>
                          </div>
                        </div>
                        <div class="item">
                          <img src="<?php echo \ucare\Plugin::plugin_url( \ucare\PLUGIN_ID ); ?>assets/images/slide-4.jpg" alt="<?php _e( 'Creating tickets', 'ucare' ); ?>">
                          <div class="carousel-caption">
                            <?php _e( 'You can click on the ticket to open it and add comments as well as view the agent responses in real time.', 'ucare' ); ?>
                          </div>
                        </div>
                          
                        <?php //_e( 'Support system introduction', \ucare\PLUGIN_ID ); ?>
                      </div>

                      <!-- Controls -->
                      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    </div>
                
                    

                <?php elseif( user_can( $user, 'manage_support_tickets' ) ) : ?>
                    
                    <p><?php _e( 'Since this is your first login to the system, please take a moment to get familiar with how it works!', 'ucare' ); ?></p>
                    <br>

                    <script src="<?php echo \ucare\Plugin::plugin_url( \ucare\PLUGIN_ID ) . '/assets/js/first_login_agent.js'; ?>"></script>

                    <iframe width="100%" height="400" src="https://www.youtube.com/embed/ZX1oAGWmFh0?rel=0&amp;controls=0&amp;showinfo=0&amp;autoplay=1 " frameborder="0" allowfullscreen></iframe>

                <?php endif; ?>


            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary" data-dismiss="modal"><?php _e( 'Close', 'ucare' ); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

