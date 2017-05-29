<?php

namespace smartcat\admin;

if( !class_exists('smartcat\admin\SettingsTab') ) :

class SettingsTab extends MenuPageTab {

    protected $sections = array();

    public function __construct( array $args ) {
        parent::__construct( $args );

        if( isset( $args['sections'] ) ) {
            foreach ( $args['sections'] as $section ) {
                $this->sections [ $section->get_slug() ] = $section;
            }
        }

        $this->init();
    }

    public function init() {
        add_action( 'admin_init', array( $this, 'register_sections' ) );
    }

    public function add_section( SettingsSection $section ) {
        $this->sections[ $section->get_slug() ] = $section;
    }

    public function register_sections() {
        foreach( $this->sections as $section ) {
            $section->register( $this->slug );
        }
    }

    public function render() { ?>

        <form method="post" action="options.php">

            <?php settings_fields( $this->slug ); ?>

            <?php do_settings_sections( $this->slug ); ?>

            <?php submit_button(); ?>

        </form>

    <?php }

}

endif;