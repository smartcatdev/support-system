<?php

namespace SmartcatSupport\descriptor;

/**
 * Constant keys for use with calls to get_option()
 * 
 * @since 1.0.0
 * @package desc
 * @author Eric Green <eric@smartcat.ca>
 */
final class Option {
    
    /**
     * @since 1.0.0
     */
    const PLUGIN_VERSION = 'ca.smartcat.support.version';
    
    /**
     * @since 1.0.0
     */
    const NUKE = 'ca.smartcat.support.erase';

    const STATUSES = 'ca.smartcat.support.statuses';

    const TICKET_CREATED_MSG = 'ca.smartcat.support.string.ticket_created';
    const TICKET_UPDATED_MSG = 'ca.smartcat.support.string.ticket_updated';
    const EMPTY_TABLE_MSG = 'ca.smartcat.support.string.empty_table';

    const CREATE_BTN_TEXT = 'ca.smartcat.support.string.create_ticket_btn';
    const REPLY_BTN_TEXT = 'ca.smartcat.support.string.reply_btn';
    const CANCEL_BTN_TEXT = 'ca.smartcat.support.string.cancel_btn';
    const SAVE_BTN_TEXT = 'ca.smartcat.support.string.save_ticket_btn';
}
