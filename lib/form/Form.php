<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\Form' ) ) :

class Form {

    protected $id;
    protected $fields = [ ];
    protected $method;
    protected $action;
    protected $valid = false;
    protected $valid_values = [ ];
    protected $errors = [ ];

    public function __construct( $id, array $fields, $method, $action ) {
        $this->id = $id;
        $this->fields = $fields;
        $this->method = strtoupper( $method );
        $this->action = $action;
    }

    public function is_valid() {
        $valid = true;

        if ( $this->is_submitted() ) {
            foreach ( $this->fields as $id => $field ) {
                if ( $field->validate( $_REQUEST[ $id ] ) ) {
                    $this->valid_values[ $id ] = $field->sanitize( $_REQUEST[ $id ] );
                } else {
                    $this->errors[ $id ] = $field->get_error_message();
                    $valid = false;
                }
            }

            $this->valid = $valid;
        }

        return $valid;
    }

    public function is_submitted() {
        return isset( $_REQUEST[ $this->id ] );
    }

    public function get_data() {
        return $this->valid_values;
    }

    public function get_errors() {
        return $this->errors;
    }

    public function get_fields() {
        return $this->fields;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_method() {
        return $this->method;
    }

    public function get_action() {
        return $this->action;
    }

    public function set_id( $id ) {
        $this->id = $id;
    }

    // <editor-fold defaultstate="collapsed" desc="Display Logic">
    public static function form_start( Form $form ) { ?>

        <form id="<?php esc_attr_e( $form->id ); ?>"
              method="<?php esc_attr_e( $form->get_method() ); ?>"
              action="<?php esc_attr_e( $form->get_action() ); ?>">

    <?php }

        public static function form_fields( Form $form ) { ?>

        <?php foreach ( $form->get_fields() as $field ) : ?>

            <p>

                <?php if ( $field->get_label() != null ) : ?>

                    <label>
                        <?php echo $field->get_label(); ?>
                    </label>

                <?php endif; ?>


                    <?php $field->render(); ?>

                <?php if ( $field->get_desc() != null ) : ?>

                    <p class="description">
                        <?php echo $field->get_desc(); ?>
                    </p>

                <?php endif; ?>

            </p>

        <?php endforeach; ?>

        <input type="hidden" name="<?php esc_attr_e( $form->id ); ?>" />

    <?php }

    public static function form_end( Form $form ) { ?>

        </form>

    <?php }

// </editor-fold>
}

endif;