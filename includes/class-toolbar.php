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

        <div id="the-toolbar">

            <div class="navbar navbar-default">

                <div class="container-fluid">

                    <ul class="nav navbar-nav">

                        <li>

                            <a href="#" class="toolbar-item-toggle">
                                <input name="bulk_action_active" type="checkbox" />
                                <span class="glyphicon check-feedback"></span> <? _e( 'Bulk Action', 'ucare' ); ?>
                            </a>

                        </li>

                    </ul>

                </div>

            </div>

            <div id="toolbar-ribbon" class="container-fluid">

                <div class="row inner">

                    <div id="bulk-action" class="col-sm-3">

                        <div class="input-group">

                            <select id="selected-bulk-action" class="form-control">
                                <option value="delete"><?php _e( 'Delete', 'ucare' ); ?></option>
                            </select>

                            <div class="input-group-btn">
                                <button id="apply-bulk-action" class="btn btn-default" disabled>
                                    <?php _e( 'Apply', 'ucare' ); ?>
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php }

}