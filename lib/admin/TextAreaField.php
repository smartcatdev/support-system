<?php
namespace smartcat\admin;


class TextAreaField extends SettingsField {

    public function render( array $args ) { ?>

        <textarea id="<?php esc_attr_e( $this->id ); ?>"
               name="<?php esc_attr_e( $this->option ); ?>"
               class="regular-text

                <?php foreach( $this->class as $class ) : ?>
                   <?php esc_attr_e( $class ); ?>
                <?php endforeach; ?>" ><?php echo $this->value; ?></textarea>

        <?php if( !empty( $this->desc ) ) : ?>

            <p class="description"><?php echo $this->desc; ?></p>

        <?php endif; ?>

    <?php }

}