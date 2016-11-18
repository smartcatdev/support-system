<?php 

use const SmartcatSupport\TEXT_DOMAIN;

?>

<table id="support_tickets_table">

    <thead>

        <tr>

            <?php foreach( $headers as $col => $value ) : ?>

                <th data-id="<?php echo $col; ?>">

                    <?php esc_html_e( $value, TEXT_DOMAIN ); ?>

                </th>

            <?php endforeach; ?>

        </tr>

    </thead>

    <tbody>

        <?php foreach( $data as $row ) : ?>

            <tr>

                <?php foreach( $row as $col => $value ) : ?>

                    <td><?php _e( $value ); ?></td>

                <?php endforeach; ?>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>
