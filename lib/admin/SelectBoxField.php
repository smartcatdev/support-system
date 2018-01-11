<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\CSelectBoxField' ) ) :
    /**
     * @deprecated
     */
    class SelectBoxField extends SettingsField {

        protected $options;

        public function __construct( array $args ) {
            parent::__construct( $args );

            $this->options = $args['options'];
        }

        public function render( array $args ) { ?>

            <select id="<?php esc_attr_e( $this->id ); ?>"
                name="<?php esc_attr_e( $this->option ); ?>"

                <?php $this->props(); ?>
                <?php $this->classes(); ?> >

                <?php foreach( $this->options as $option => $title ) : ?>

                    <option value="<?php esc_attr_e( $option ); ?>"

                        <?php selected( $option, $this->value ); ?>>

                        <?php esc_html_e( $title ); ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <?php if( !empty( $this->desc ) ) : ?>

                <p class="description"><?php echo $this->desc; ?></p>

            <?php endif; ?>

        <?php }
    }

endif;