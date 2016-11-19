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

    /**
     * @since 1.0.0
     */
    const STATUSES = 'ca.smartcat.support.statuses';

    /**
     * @since 1.0.0
     */
    const WOOCOMMERCE_ACTIVE = 'ca.smartcat.support.woocommerce_active';

    /**
     * @since 1.0.0
     */
    const EDD_ACTIVE = 'ca.smartcat.support.edd_active';

    /**
     * @since 1.0.0
     */
    const TICKET_CREATED_MSG = 'ca.smartcat.support.string.ticket_created';

    /**
     * @since 1.0.0
     */
    const TICKET_UPDATED_MSG = 'ca.smartcat.support.string.ticket_updated';

    /**
     * @since 1.0.0
     */
    const EMPTY_TABLE_MSG = 'ca.smartcat.support.string.empty_table';

    /**
     * @since 1.0.0
     */
    const CREATE_BTN_TEXT = 'ca.smartcat.support.string.create_ticket_btn';

    /**
     * @since 1.0.0
     */
    const REPLY_BTN_TEXT = 'ca.smartcat.support.string.reply_btn';

    /**
     * @since 1.0.0
     */
    const CANCEL_BTN_TEXT = 'ca.smartcat.support.string.cancel_btn';

    /**
     * @since 1.0.0
     */
    const SAVE_BTN_TEXT = 'ca.smartcat.support.string.save_ticket_btn';
}
