/**
 * Main plugin module, provides services and utilities used throughout the application.
 *
 * @since 1.6.0
 */
;var ucare = (function ($, exports, localize) {
    "use strict";

    // Initialize dynamic variables
    Object.assign(exports, localize);


    /**
     * Set the rest nonce in the request headers.
     *
     * @param xhr
     *
     * @access private
     * @since 1.6.0
     * @return void
     */
    function set_rest_nonce(xhr) {
        xhr.setRequestHeader('X-WP-Nonce', localize.api.nonce);
    }


    /**
     * Basic Pub/Sub event bus.
     *
     * @constructor
     *
     * @since 1.6.0
     * @return {object}
     */
    const EventBus = function () {

        // Internal hooks
        const callbacks = jQuery.Callbacks();

        // Remap function names
        return {
            publish: callbacks.fire,
            subscribe: callbacks.add,
            unsubscribe: callbacks.remove
        };

    };

    // Export our bus
    exports.EventBus = EventBus;


    /**
     * @summary Internal global event messaging system.
     *
     * @since 1.6.0
     * @type {object}
     */
    exports.events = {

        /**
         * @summary List of default registered broadcast channels.
         */
        channels: {

            /**
             * @summary Channel for the current user object.
             *
             * @since 1.6.0
             */
            CURRENT_USER: 'channel_current_user'

        },

        /**
         * @summary List of registered channels.
         */
        _channels: {},

        /**
         * @summary Add a callback to a channel.
         *
         * @param {string}   channel
         * @param {Function} callback
         *
         * @since 1.6.0
         * @return {EventBus}
         */
        subscribe: function (channel, callback) {

            if (!this._channels[channel]) {
                this._channels[channel] = new EventBus();
            }

            this._channels[channel].subscribe(callback);

            // Return the event bus instance
            return this._channels[channel];

        },

        /**
         * Remove a callback from a channel.
         *
         * @param {string}   channel
         * @param {Function} callback
         *
         * @since 1.6.0
         * @return {void}
         */
        unsubscribe: function (channel, callback) {
            if (this._channels[channel]) {
                this._channels[channel].unsubscribe(callback);
            }
        },

        /**
         * Publish an event on the bus.
         *
         * @param {string} channel
         * @param {*}      data
         *
         * @since 1.6.0
         * @return void
         */
        publish: function (channel, data) {
            if (this._channels[channel]) {
                this._channels[channel].publish(data);
            }
        }

    };


    /**
     * Utilities for managing user profile data.
     *
     * @since 1.6.0
     * @type {object}
     */
    exports.user = {

        /**
         * @summary Update a user.
         *
         * @param {object} user
         *
         * @since 1.6.0
         * @return {Deferred}
         */
        update: function (user) {

            return $.ajax({
                data: user,
                method: 'post',
                url: ucare.api.root + 'wp/v2/users/me',
                beforeSend: set_rest_nonce,
                success: function (user) {
                    ucare.events.publish(ucare.events.channels.CURRENT_USER, user);
                }
            });

        }

    };


    /**
     * @summary Export our module
     */
    return exports;

})(jQuery, ucare || {}, ucare_i10n);