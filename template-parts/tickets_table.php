<?php

use const SmartcatSupport\PLUGIN_NAME;

?>

<table id="support_tickets_table" class="display" cellspacing="0" width="100%">

    <thead>

        <tr>

            <?php foreach( $headers as $col => $title ) : ?>

                <th data-column_name="<?php echo $col; // For dynamically generating column names client-side ?>">

                    <?php esc_html_e( $title, PLUGIN_NAME ); ?>

                </th>

            <?php endforeach; ?>

        </tr>

    </thead>

    <tbody>

        <?php foreach( $data as $row ) : ?>

            <tr>

                <?php foreach( $row as $col => $value ) : ?>

                    <td><?php echo $value; ?></td>

                <?php endforeach; ?>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>
