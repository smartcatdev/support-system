<?php

use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div id="support_system">

    <?php if( current_user_can( 'create_support_tickets' ) ) : ?>

        <a href="<?php echo admin_url( 'admin-ajax.php' ) . '?action=support_new_ticket'?>"  rel="modal:open">

            <?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ), TEXT_DOMAIN ); ?>

        </a>

    <?php endif; ?>

        <div class="tabs">

            <ul>

                <li>

                    <a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=support_list_tickets">

                        <?php _e( 'Tickets', TEXT_DOMAIN ); ?>

                    </a>

                </li>

            </ul>

        </div>

</div>
