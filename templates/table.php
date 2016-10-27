<?php 

use const SmartcatSupport\TEXT_DOMAIN;

?>

<table id="<?php esc_attr_e( $id ); ?>">

    <thead>

        <tr>

            <?php foreach( $headers as $col => $value ) : ?>

                <th><?php esc_html_e( __( $value, TEXT_DOMAIN ) ); ?></th>

            <?php endforeach; ?>

        </tr>

    </thead>

    <tbody>

        <?php foreach( $data as $row ) : ?>

            <tr>

                <?php foreach( $row as $data => $value ) : ?>

                    <td><?php esc_html_e( $value ); ?></td>

                <?php endforeach; ?>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>
