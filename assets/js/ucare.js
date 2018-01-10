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
    const EventEmitter = function () {

        // Internal events
        const events = {};

        return {

            /**
             * @summary Add a callback to a channel.
             *
             * @param {string}   e
             * @param {Function} callback
             *
             * @since 1.6.0
             * @return {void}
             */
            on: function (e, callback) {
                if (!events[e]) {
                    events[e] = jQuery.Callbacks();
                }

                events[e].add(callback);
            },

            /**
             * Remove a callback from a channel.
             *
             * @param {string}   e
             * @param {Function} callback
             *
             * @since 1.6.0
             * @return {void}
             */
            off: function (e, callback) {
                if (events[e]) {
                    events[e].remove(callback)
                }
            },

            /**
             * Publish an event on the bus.
             *
             * @param {string} e
             * @param {*}      data
             *
             * @since 1.6.0
             * @return void
             */
            emit: function (e, data) {
                if (events[e]) {
                    var args = [];
                    for (var ctr = 1; ctr < arguments.length; ctr++) {
                        args.push(arguments[ctr]);
                    }

                    // Fill for spread (...obj)
                    events[e].fire.apply(events[e], args);
                }
            }
        }

    };

    /**
     * @summary Internal global event messaging system.
     *
     * @since 1.6.0
     * @type {object}
     */
    const events = new EventEmitter();


    // Export our bus
    exports.EventEmitter = EventEmitter;

    // Export the events
    exports.events = events;


    /**
     * @summary Default system action types.
     *
     * @private
     * @since 1.6.0
     */
    const ActionTypes = {
        /**
         * @since 1.6.0
         */
        SET_TOOLBAR_TOGGLE: 'SET_TOOLBAR_TOGGLE',

        /**
         * @since 1.6.0
         */
        BULK_SELECT_ITEM: 'BULK_SELECT_ITEM',

        /**
         * @since 1.6.0
         */
        BULK_DESELECT_ITEM: 'BULK_DESELECT_ITEM',

        /**
         * @since 1.6.0
         */
        TICKET_DELETED: 'TICKET_DELETED'
    };


    /**
     * @summary Internal store reducer functions.
     *
     * @since 1.6.0
     */
    const reducers = {

        /**
         * @since 1.6.0
         */
        toolbar: function (state, action) {
            const copy = $.extend(true, {}, state);

            switch (action.type) {
                case ActionTypes.SET_TOOLBAR_TOGGLE:
                    copy[action.data.toggle] = action.data.value;
                    break;
            }

            return copy;
        },

        /**
         * @since 1.6.0
         */
        tickets: function (state, action) {
            var index = -1,
                copy  = $.extend(true, { selected: [] }, state);

            switch(action.type) {
                case ActionTypes.BULK_SELECT_ITEM:
                    if (copy.selected.indexOf(action.data.id) < 0) {
                        copy.selected.push(action.data.id);
                    }

                    break;

                case ActionTypes.BULK_DESELECT_ITEM:
                    index = copy.selected.indexOf(action.data.id);
                    if (index > -1) {
                        copy.selected.splice(index, 1);
                    }

                    break;

                case ActionTypes.TICKET_DELETED:

                    // Remove the ticket from the selected list
                    index = copy.selected.indexOf(action.data.id);
                    if (index > -1) {
                        copy.selected.splice(index, 1);
                    }

                    break;
            }

            return copy;
        }
    };


    /**
     * @summary Initialize the application store.
     *
     * @since 1.6.0
     */
    const store = Redux.createStore(Redux.combineReducers(reducers));

    // Export the store
    exports.store = store;



    /**
     * @summary Default system actions.
     *
     * @since 1.6.0
     */
    exports.Actions = {

        /**
         * Set activate or deactivate a toolbar action.
         *
         * @param {string} toggle
         * @param {*}      value
         *
         * @since 1.6.0
         * @return void
         */
        setToolbarToggle(toggle, value) {
            store.dispatch({
                type: ActionTypes.SET_TOOLBAR_TOGGLE,
                data: {
                    toggle: toggle,
                    value:  value
                }
            });
        },

        /**
         * Select an item in the tickets list.
         *
         * @param {int} id
         *
         * @since 1.6.0
         * @return {void}
         */
        selectTicket(id) {
            store.dispatch({
                type: ActionTypes.BULK_SELECT_ITEM,
                data: {
                    id: id
                }
            });
        },

        /**
         * Deselect a selected item in the tickets list.
         *
         * @param {int} id
         *
         * @since 1.6.0
         * @return {void}
         */
        deselectTicket(id) {
            store.dispatch({
                type: ActionTypes.BULK_DESELECT_ITEM,
                data: {
                    id: id
                }
            });
        },

        /**
         * Delete a ticket.
         *
         * @param {int} id
         *
         * @since 1.6.0
         * @return {void}
         */
        deleteTicket(id) {
            $.ajax({
                url: localize.api.root + 'wp/v2/support-tickets/' + id,
                method: 'delete',
                beforeSend: set_rest_nonce
            })
            .success(function () {
                events.emit('ticket_deleted', id);
                store.dispatch({
                    type: ActionTypes.TICKET_DELETED,
                    data: {
                        id: id
                    }
                });
            });
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
                beforeSend: set_rest_nonce
            });
        }

    };

    /**
     * @summary Export our module
     */
    return exports;

})(jQuery, ucare || {}, ucare_l10n);