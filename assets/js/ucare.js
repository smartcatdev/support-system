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
            publish:     callbacks.fire,
            subscribe:   callbacks.add,
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
            CURRENT_USER: 'channel_current_user',

            /**
             * @summary Channel for new registered users.
             *
             * @since 1.6.0
             */
            REGISTER_USER: 'channel_register_user'

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
                const selected = this._channels[channel];

                var args = [];
                for (var ctr = 1; ctr < arguments.length; ctr++) {
                    args.push(arguments[ctr]);
                }

                // Fill for spread (...obj)
                selected.publish.apply(selected.publish, args);
            }
        }

    };

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
        SET_BULK_ACTION: 'SET_BULK_ACTION'
    };

    /**
     * @summary Default system actions.
     *
     * @since 1.6.0
     */
    exports.Actions = {

        /**
         * Set the Bulk action for the tickets list.
         *
         * @param {string} action
         *
         * @since 1.6.0
         * @return void
         */
        setBulkAction(action) {
            dispatcher.dispatch({
                type: ActionTypes.SET_BULK_ACTION,
                action: action
            });
        }

    };

    /**
     * @summary Dispatcher constructor.
     *
     * @since 1.6.0
     * @constructor
     */
    const Dispatcher = function () {
        this.handlers = jQuery.Callbacks();
    };

    /**
     * @summary Registers a handler that will receive dispatched sate changes.
     *
     * @param {Function} handler
     *
     * @since 1.6.0
     * @return {void}
     * @todo return a token
     */
    Dispatcher.prototype.register = function (handler) {
        this.handlers.add(handler);
    };

    /**
     * @summary Remove a dispatch handler.
     *
     * @param {Function} handler
     *
     * @since 1.6.0
     * @return void
     */
    Dispatcher.prototype.unregister = function (handler) {
        this.handlers.remove(handler);
    };

    /**
     * @summary Dispatch a state change.
     *
     * @param {*} payload
     *
     * @since 1.6.0
     * @return {void}
     */
    Dispatcher.prototype.dispatch = function (payload) {
        this.handlers.fire(payload);
    };

    // Export the dispatcher
    exports.Dispatcher = Dispatcher;


    /**
     * @summary System dispatcher
     */
    const dispatcher = new Dispatcher();

    /**
     * @summary Base class for a reducer store.
     *
     * @param {Dispatcher} dispatcher
     *
     * @since 1.6.0
     * @constructor
     */
    const Store = function (dispatcher) {
        this.dispatcher = dispatcher;
        this.events = new EventBus();
        this.state  = this.getInitialState();

        // Setup reduction
        this.dispatcher.register(onDispatch.bind(this));

        /**
         * Merge the state with our store's state on dispatch
         *
         * @param payload
         *
         * @since 1.6.0
         * @return {void}
         */
        function onDispatch (payload) {
            const state = this.reduce(this.state, payload);

            if (!exports.ext.compare(this.state, state)) {
                this._setState(state);
                this._emitChange();
            }
        }

        /**
         * Sets the state.
         *
         * @param {*} state
         * @private
         * @since 1.6.0
         */
        this._setState = function(state) {
            this.state = state;
        }

    };

    /**
     * @summary Emit an event when the store has changed.
     *
     * @since 1.6.0
     * @private
     * @return {void}
     */
    Store.prototype._emitChange = function () {
        this.events.publish(this.state);
    };

    /**
     * @summary Add a listener for state change events.
     *
     * @param {Function} callback
     *
     * @since 1.6.0
     * @return {void}
     */
    Store.prototype.subscribe = function (callback) {
        this.events.subscribe(callback);
    };

    /**
     * @summary Remove a registered event listener from the store.
     *
     * @param {Function} callback
     *
     * @since 1.6.0
     * @return {void}
     */
    Store.prototype.unsubscribe = function (callback) {
        this.events.unsubscribe(callback);
    };

    /**
     * @summary Get the initial state of the store. This value will be used to initialize the store.
     *
     * @since 1.6.0
     * @return {*}
     */
    Store.prototype.getInitialState = function () {
        return {};
    };

    /**
     * @summary Handler merging of state from a dispatched action.
     *
     * @param {*} state
     * @param {*} action
     *
     * @since 1.6.0
     * @return {*}
     */
    Store.prototype.reduce = function (state, action) {};

    // Export our base store
    exports.Store = Store;


    /**
     * @summary Store for toolbar state.
     *
     * @param {Dispatcher} dispatcher
     * @constructor
     */
    const ToolbarStore = function (dispatcher) {
        Store.call(this, dispatcher);
    };

    // Inherit from store
    $.extend(ToolbarStore.prototype, Store.prototype);

    /**
     *
     * @param state
     * @param action
     *
     * @return {*}
     * @since 1.6.0
     */
    ToolbarStore.prototype.reduce = function (state, action) {
        switch (action.type) {
            case ActionTypes.SET_BULK_ACTION:
                return action.action;
                break;

            default:
                return state;
                break;
        }
    };

    /**
     *
     * @return {*}
     * @since 1.6.0
     */
    ToolbarStore.prototype.getInitialState = function () {
        return '';
    };


    /**
     * @summary System store objects.
     *
     * @since 1.6.0
     */
    exports.stores = {
        toolbarStore: new ToolbarStore(dispatcher)
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

})(jQuery, ucare || {}, ucare_l10n);