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
        SET_TOOLBAR_TOGGLE: 'SET_TOOLBAR_TOGGLE',

        /**
         * @since 1.6.0
         */
        BULK_SELECT_ITEM: 'BULK_SELECT_ITEM',

        /**
         * @since 1.6.0
         */
        BULK_DESELECT_ITEM: 'BULK_DESELECT_ITEM',
    };

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
            dispatcher.dispatch({
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
         * @param {int|string} id
         *
         * @since 1.6.0
         * @return {void}
         */
        selectTicket(id) {
            dispatcher.dispatch({
                type: ActionTypes.BULK_SELECT_ITEM,
                data: {
                    id: id
                }
            });
        },

        /**
         * Deselect a selected item in the tickets list.
         *
         * @param {int|string} id
         *
         * @since 1.6.0
         * @return {void}
         */
        deselectTicket(id) {
            dispatcher.dispatch({
                type: ActionTypes.BULK_DESELECT_ITEM,
                data: {
                    id: id
                }
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
        this._handlers = jQuery.Callbacks();
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
        this._handlers.add(handler);
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
        this._handlers.remove(handler);
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
        this._handlers.fire(payload);
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
        this._events = new EventBus();
        this._state  = this.getInitialState();

        // Setup reduction
        dispatcher.register(this.onDispatch.bind(this));
    };

    /**
     * Sets the state.
     *
     * @param {*} state
     * @private
     * @since 1.6.0
     */
    Store.prototype._setState = function(state) {
        this._state = state;
    };

    /**
     * @summary Emit an event when the store has changed.
     *
     * @since 1.6.0
     * @private
     * @return {void}
     */
    Store.prototype._emitChange = function () {
        this._events.publish(this);
    };

    /**
     * @summary Return the current store state.
     *
     * @since 1.6.0
     * @return {*}
     */
    Store.prototype.getState = function () {
        return $.extend(true, {}, this._state);
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
        this._events.subscribe(callback);
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
        this._events.unsubscribe(callback);
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
    Store.prototype.reduce = function (state, action) { return state };

    /**
     * Merge the state with our store's state on dispatch
     *
     * @param payload
     *
     * @since 1.6.0
     * @return {void}
     */
    Store.prototype.onDispatch = function (payload) {
        const current = this.getState(),
              mutated = this.reduce(current, payload);

        // If the state has actually mutated
        if (!exports.ext.compare(current, mutated)) {
            this._setState(mutated);
            this._emitChange();
        }
    };

    // Export our base store
    exports.Store = Store;


    /**
     * @summary Factory for creating Flux stores.
     *
     * @param {Dispatcher} dispatcher
     * @param {Function}   reducer
     * @param {*}          initialState
     *
     * @since 1.6.0
     * @return {Store}
     */
    const createStore = function createStore(dispatcher, reducer, initialState) {
        const store = new Store(dispatcher);

        // Override the store prototype
        store.prototype.reduce = reducer;
        store.prototype.getInitialState = function () {
            return initialState;
        }
    };

    // Export factory
    exports.createStore = createStore;


    /**
     * @summary Store for toolbar state.
     *
     * @param {Dispatcher} dispatcher
     * @since 1.6.0
     * @constructor
     */
    const ToolbarStore = function (dispatcher) {
        Store.call(this, dispatcher);
    };

    // Inherit from store
    $.extend(ToolbarStore.prototype, Store.prototype);

    /**
     *
     * @param {*}                       state
     * @param {{type: string, data: *}} action
     *
     * @return {*}
     * @since 1.6.0
     */
    ToolbarStore.prototype.reduce = function (state, action) {
        const copy = $.extend(true, {}, state);

        switch (action.type) {
            case ActionTypes.SET_TOOLBAR_TOGGLE:
                copy[action.data.toggle] = action.data.value;
                break;
        }

        return copy;
    };

    /**
     *
     * @return {*}
     * @since 1.6.0
     */
    ToolbarStore.prototype.getInitialState = function () {
        return {
            'bulk-action': ''
        };
    };


    /**
     * @summary Store for the tickets list in the dashboard.
     *
     * @param {Dispatcher} dispatcher
     *
     * @since 1.6.0
     * @constructor
     */
    const TicketsStore = function (dispatcher) {
        Store.call(this, dispatcher);
    };

    // Inherit from store
    $.extend(TicketsStore.prototype, Store.prototype);


    /**
     *
     * @return {*}
     * @since 1.6.0
     */
    TicketsStore.prototype.getInitialState = function () {
        return {
            selected: []
        };
    };

    /**
     *
     * @param {*}                       state
     * @param {{type: string, data: *}} action
     *
     * @return {*}
     * @since 1.6.0
     */
    TicketsStore.prototype.reduce = function (state, action) {
        const copy = $.extend(true, {}, state);

        switch(action.type) {
            case ActionTypes.BULK_SELECT_ITEM:
                if (copy.selected.indexOf(action.data.id) < 0) {
                    copy.selected.push(action.data.id);
                }

                break;

            case ActionTypes.BULK_DESELECT_ITEM:
                const index = state.selected.indexOf(action.data.id);
                if (index > -1) {
                    copy.selected.splice(index, 1);
                }

                break;
        }

        return copy;
    };


    /**
     * @summary System store objects.
     *
     * @since 1.6.0
     */
    exports.stores = {
        toolbar: new ToolbarStore(dispatcher),
        tickets: new TicketsStore(dispatcher)
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