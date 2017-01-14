<?php

namespace SmartcatSupport\admin;

use smartcat\post\AbstractMetaBox;

class FormMetaBox extends AbstractMetaBox {
    private $config;

    public function __construct( array $args ) {
        parent::__construct( $args );

        $this->config = $args['config'];
    }

    public function render( \WP_Post $post ) { ?>

        <?php $form = include $this->config; ?>

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

    <?php }

    public function save( $post_id, \WP_Post $post ) {
        $form = include $this->config;

        if( $form->is_valid() ) {
            foreach( $form->data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
