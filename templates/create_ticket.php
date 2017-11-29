<?php

use ucare\Plugin;

$form = include Plugin::plugin_dir( \ucare\PLUGIN_ID ) . '/config/ticket_create_form.php';

?>

<div id="create_ticket" class="form-wrapper">

    <form id="create-ticket-form">

        <?php if ( \ucare\util\can_manage_tickets() ) : ?>

            <div class="form-group">

                <p id="toggle-set-author">

                    <label data-target="#select-author" data-toggle="collapse">
                        <input type="checkbox" name="override_author"> <?php _e( 'Manually set ticket creator', 'ucare' ); ?>
                    </label>

                </p>

                <div id="select-author" class="collapse">

                    <label for="ticket-author"><?php _e( 'Created by', 'ucare' ); ?></label>

                    <select id="ticket-author"
                            class="form-control"
                            name="author"
                            data-default="<?php esc_attr_e( get_current_user_id() ); ?>">

                        <option value="<?php esc_attr_e( get_current_user_id() ); ?>">
                            <?php _e( 'Me', 'ucare' ); ?>
                        </option>

                        <?php $users = \ucare\get_users_with_cap( 'use_support', array( 'exclude' => array( get_current_user_id() ) ) ); ?>

                        <?php foreach ( $users as $user ) : ?>

                            <option value="<?php esc_attr_e( $user->ID ); ?>">
                                <?php esc_html_e( $user->display_name ); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

        <?php endif; ?>

        <?php foreach( $form->fields as $name => $field ) : ?>

            <div class="form-group">

                <label for="<?php echo $field->id; ?>"><?php echo $field->label; ?></label>

                <?php $field->render(); ?>

            </div>

        <?php endforeach; ?>

        <input type="hidden" class="attachments" name="attachments" value="[]" data-default="[]" />
        <input type="hidden" name="<?php echo $form->id; ?>" />

    </form>

    <div class="form-group">

        <label><?php _e( 'Attach Images', 'ucare' ); ?></label>

        <form id="ticket-media-upload" class="dropzone">

            <?php wp_nonce_field( 'support_ajax', '_ajax_nonce' ); ?>

        </form>

    </div>

</div>
