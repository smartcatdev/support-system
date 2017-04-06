<?php

namespace SmartcatSupport\component;


use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;

class Notifications extends AbstractComponent {

    public function user_register( $user_data ) {
        Mailer::send_template( get_option( Option::WELCOME_EMAIL_TEMPLATE ), $user_data['email'], $user_data );
    }

    public function ticket_updated() {

    }

    public function ticket_created() {

    }

    public function ticket_reply() {

    }

    public function subscribed_hooks() {
        return array(
            'post_support_user_register' => array( 'user_register' )
        );
    }
}