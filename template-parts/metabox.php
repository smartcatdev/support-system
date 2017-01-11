<div class="support_ticket_metabox">

    <table class="form-table">

        <?php foreach( $form->fields as $field ) : ?>

            <tr>
                <?php if( !empty( $field->label ) ) : ?>

                    <th>
                        <label><?php echo $field->label; ?></label>
                    </th>

                <?php endif; ?>

                <td>
                    <?php $field->render(); ?>

                    <?php if( !empty( $field->desc ) ) : ?>

                        <p class="description"><?php echo $field->desc; ?></p>

                    <?php endif; ?>

                </td>
            </tr>

        <?php endforeach; ?>

        <tr>
            <td style="display: none">
                <input type="hidden" name="<?php esc_attr_e( $form->id ); ?>"/>
            </td>
        </tr>

    </table>

</div>
