<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>


    <?php if( !empty( $data ) ) : ?>

        <table id="tickets-list" class="display" cellspacing="0" width="100%">

            <thead>

            <tr>

                <?php foreach( $headers as $col => $title ) : ?>

                    <th data-column_name="<?php echo $col; // For dynamically generating column names client-side ?>">

                        <?php esc_html_e( $title ); ?>

                    </th>

                <?php endforeach; ?>

            </tr>

            </thead>

            <tbody>

            <?php foreach( $data as $ticket ) : ?>

                <tr class="<?php echo get_post_meta( $ticket->ID, 'status', true ); ?>">

                    <?php foreach( $headers as $col => $title ) : ?>

                        <td><?php do_action( 'support_tickets_table_column_data', $col, $ticket ) ?></td>

                    <?php endforeach; ?>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="message">

            <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ) ); ?></p>

        </div>

    <?php endif; ?>
