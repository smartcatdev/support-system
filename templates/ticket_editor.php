<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket_editor">

    <form data-action="<?php esc_attr_e( $action ); ?>"
          data-after="<?php esc_attr_e( $after ); ?>">

        <?php Form::form_fields( $editor_form ); ?>

        <div class="meta">

            <?php Form::form_fields( $meta_form ); ?>

        </div>

        <div class="button_wrapper">

            <button class="button cancel"><?php _e( 'Cancel', TEXT_DOMAIN ); ?></button>

            <button class="button submit">

                <div class="status hidden"></div>

                <span class="text"
                      data-default="<?php _e( 'Save', TEXT_DOMAIN ); ?>"
                      data-success="<?php _e( 'Saved', TEXT_DOMAIN ); ?>"
                      data-fail="<?php _e( 'Error', TEXT_DOMAIN ); ?>"
                      data-wait="<?php _e( 'Saving', TEXT_DOMAIN ); ?>">

                          <?php _e( 'Save', TEXT_DOMAIN ); ?>

                    </span>

            </button>

        </div>

    </form>

</div>
