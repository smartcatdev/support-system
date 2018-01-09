<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

/**
 * Class for the context toolbar displayed at the top of pages in the front-end application.
 *
 * @since 1.6.0
 * @package ucare
 */
class Toolbar {

    protected $nodes = array();


    public function initialize() {
        do_action( 'ucare_toolbar_init', $this );

        ucare_enqueue_script( 'ucare-toolbar', resolve_url( 'assets/js/toolbar.js'   ), array( 'ucare' ), PLUGIN_VERSION );
        ucare_enqueue_style ( 'ucare-toolbar', resolve_url( 'assets/css/toolbar.css' ), null, PLUGIN_VERSION );
    }


    public function add_node( $node ) {
        // TODO make this toolbar extendable
    }


    public function render() {
        ?>

        <div id="the-toolbar" class="navbar navbar-default">

            <div class="container-fluid">

            <ul class="nav navbar-nav">

                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <? _e( 'Bulk Actions', 'ucare' ); ?>
                    </a>

                    <ul class="dropdown-menu">

                        <li>
                            <a href="#" class="toolbar-item-toggle">
                                <span class="glyphicon check-feedback"></span>
                                <input name="bulk_action" type="checkbox" value="delete"> <?php _e( 'Delete', 'ucare' ); ?>
                            </a>
                        </li>

                    </ul>

                </li>

            </ul>

            </div>

        </div>

    <?php }

}