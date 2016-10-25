<?php

namespace SmartcatSupport\util;

use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Description of datatable
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class DataTable {
    private $headings;
    private $data;
    private $footer;
    
    public function __construct( array $headings, array $data ) {
        $this->headings = $headings;
        $this->data = $data;
        $this->footer = true;
    }
    
    public function build() { ?>
        <table class="data_table">
            <tr>
                <?php $this->headings(); ?>
            </tr>
            <tr>
                <?php $this->body(); ?>
            </tr>
            <tr>
                <?php $this->headings(); ?>
            </tr>
        </table>
    <?php }
    
    private function headings() {
        foreach( $this->headings as $heading ) : ?>         
            <th>
                <?php esc_html_e( $heading, TEXT_DOMAIN ) ?>
            </th>
        <?php endforeach;

    }
    
    private function body() {
        foreach( $this->data as $data ) : ?>
            <td>
                <?php esc_html_e( $data, TEXT_DOMAIN ); ?>
            </td>
        <?php endforeach;
    }
}
