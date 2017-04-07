<?php
namespace smartcat\admin;


class TextAreaField extends SettingsField {

    public function render( array $args ) { ?>

        <textarea id="<?php esc_attr_e( $this->id ); ?>"
               name="<?php esc_attr_e( $this->option ); ?>"

                <?php $this->props(); ?>
                <?php $this->classes(); ?> ><?php echo $this->value; ?></textarea>

        <?php if( !empty( $this->desc ) ) : ?>

            <p class="description"><?php echo $this->desc; ?></p>

        <?php endif; ?>

    <?php }

}