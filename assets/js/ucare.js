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
        SET_BULK_ACTION: 'SET_BULK_ACTION',

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
         * Set the value of the currently selected bulk action.
         *
         * @param {string} action
         *
         * @since 1.6.0
         * @return void
         */
        setBulkAction(action) {
            dispatcher.dispatch({
                type: ActionTypes.SET_BULK_ACTION,
                data: {
                    selected: action
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
         * @param {int} id
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
                dispatcher.dispatch({
                    type: ActionTypes.TICKET_DELETED,
                    data: {
                        id: id
                    }
                });
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
     * @since 1.6.0
     * @constructor
     */
    const Store = function () {
        this._events = new EventEmitter();
        this._state  = this.initialState();
    };

    /**
     * @summary Deep compare two objects.
     *
     * @since 1.6.0
     * @return {bool}
     */
    Store.prototype.compare = ucare.ext.compare;

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
     * @param {string} e
     * @param {*}      args
     *
     * @since 1.6.0
     * @private
     * @return {void}
     */
    Store.prototype._emitEvent = function (e, args) {
        this._events.emit(e, args);
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
     * @param {string}   e
     * @param {Function} callback
     *
     * @since 1.6.0
     * @return {void}
     */
    Store.prototype.on = function (e, callback) {
        this._events.on(e, callback);
    };

    /**
     * @summary Remove a registered event listener from the store.
     *
     * @param {string}   e
     * @param {Function} callback
     *
     * @since 1.6.0
     * @return {void}
     */
    Store.prototype.off = function (e, callback) {
        this._events.off(e, callback);
    };

    /**
     * @summary Get the initial state of the store. This value will be used to initialize the store.
     *
     * @since 1.6.0
     * @return {*}
     */
    Store.prototype.initialState = function () {
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
        if (!this.compare(current, mutated)) {
            this._setState(mutated);
            this._emitEvent('change', this);
        }
    };

    // Export our base store
    exports.Store = Store;


    /**
     * @summary Factory for creating Flux stores.
     *
     * @param {Dispatcher} dispatcher
     * @param {object}     options
     *
     * @since 1.6.0
     * @return {Store}
     */
    const createStore = function createStore(dispatcher, options) {
        const store = function () {
            Store.call(this);
        };

        $.extend(store.prototype, Store.prototype);

        // Override the store prototype
        store.prototype.reduce = options.reducer;
        store.prototype.initialState = options.initialState;

        const instance = new store();

        // Setup reduction
        dispatcher.register(instance.onDispatch.bind(instance));

        // Return the new store instance
        return instance;
    };

    // Export factory
    exports.createStore = createStore;


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

                case ActionTypes.SET_BULK_ACTION:
                    copy.selected_bulk_action = action.selected;
                    break;
            }

            return copy;
        },

        /**
         * @since 1.6.0
         */
        tickets: function (state, action) {
            var index = -1,
                copy  = $.extend(true, {}, state);

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
                        this._emitEvent('delete', action.data.id);
                    }

                    break;
            }

            return copy;
        }
    };


    /**
     * @summary System store objects.
     *
     * @since 1.6.0
     */
    exports.stores = {

        /**
         * @summary Store for managing the global toolbar state.
         *
         * @since 1.6.0
         */
        toolbar: createStore(dispatcher, {
            reducer: reducers.toolbar,
            initialState: function () {
                return {
                    bulk_action_active: false,
                    selected_bulk_action: 'delete' // TODO make this variable
                }
            }
        }),

        /**
         * @summary Store for managing the ticket list state.
         *
         * @since 1.6.0
         */
        tickets: createStore(dispatcher, {
            reducer: reducers.tickets,
            initialState: function () {
                return {
                    selected: []
                }
            }
        })
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