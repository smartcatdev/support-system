<?php

namespace smartcat\form;

/**
 * @deprecated
 */
class RadioGroup extends AbstractField {

    protected $options = array();

    public function __construct( array  $args ) {
        parent::__construct( $args );

        $this->options = $args['options'];
    }

    public function render() { ?>

        <ul>

         <?php foreach( $this->options as $label => $value ) : ?>

                <li>
                    <label>
                        <input name="<?php echo $this->name; ?>"
                            value="<?php echo $value; ?>"
                            type="radio"

                            <?php selected( $value, $this->value ); ?>

                            <?php $this->classes(); ?>
                            <?php $this->props(); ?> /><?php echo $label; ?></label>
                </li>

            <?php endforeach; ?>

        </ul>

    <?php }
}