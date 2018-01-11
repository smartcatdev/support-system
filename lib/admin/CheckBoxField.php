<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\CheckBoxField' ) ) :
    /**
     * Class CheckBoxField
     * @deprecated
     * @package smartcat\admin
     */
    class CheckBoxField extends SettingsField {

        public function render( array $args ) { ?>

            <input type="checkbox"
                id="<?php esc_attr_e( $this->id ); ?>"
                name="<?php esc_attr_e( $this->option ); ?>"

                <?php $this->props(); ?>
                <?php $this->classes(); ?>

                <?php checked( $this->value, 'on' ); ?> />

            <label for="<?php esc_attr_e( $this->id ); ?>"><?php echo $this->desc; ?></label>

        <?php }
    }

endif;