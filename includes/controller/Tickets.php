<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\view\TicketDetail;
use SmartcatSupport\form\Form;
use SmartcatSupport\form\TextBox;
use SmartcatSupport\form\TextArea;
use SmartcatSupport\form\Button;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class Tickets {

    public function __construct( ) {
        $form = new Form( 'ticket', '$update_ticket' );
        $form->add_field('title', new TextBox( 'title', 'Subject'));
        $form->add_field('desc', new TextArea('desc', 'Description'));
        $form->add_field('submit_button', new Button('submit_button', '', [ 'type' => 'submit', 'default' => 'Submit']));
        $ticket = new TicketDetail($form);
        add_shortcode('test', [ $ticket, 'render' ] );
    }
    
    public function process_request() {
        
    }
    
}
