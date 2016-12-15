<?php

use const SmartcatSupport\TEXT_DOMAIN;

?>
<div class="support_ticket_metabox">

    <table class="form-table">

        <?php foreach ( $form->get_fields() as $field ) : ?>

            <tr>
                <?php if ( $field->get_label() != null ) : ?>

                    <th>
                        <label>
                            <?php esc_html_e( __( $field->get_label(), TEXT_DOMAIN ) ); ?>
                        </label>
                    </th>

                <?php endif; ?>

                <td>
                    <?php $field->render(); ?>

                    <?php if ( $field->get_desc() != null ) : ?>

                        <p class="description">
                            <?php esc_html_e( __( $field->get_desc(), TEXT_DOMAIN ) ); ?>
                        </p>

                    <?php endif; ?>

                </td>
            </tr>

        <?php endforeach; ?>

        <tr>
            <td style="display: none">
                <input type="hidden" name="<?php esc_attr_e( $form->get_id() ); ?>"/>
            </td>
        </tr>

    </table>

</div>