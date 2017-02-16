var Tabs = (function(module, $, window) {
    "use strict";

    var tabs;

    module.close = function () {

    };

    module.open = function () {

    };

    module.initialize = function () {
        tabs = $("#tabs");
        tabs.tabs();
    };

    return module;

})(Tabs || {}, jQuery, window);


//
// var Comments = (function () {
//
//     var initialize_events = function() {
//
//     };
//
//     var save_comment = function () {
//
//     };
//
//     var delete_comment = function () {
//
//     };
//
//     var edit_comment = function () {
//
//     };
//
//     var load_comments = function () {
//
//     };
//
// });
//
// var Sidebar = (function() {
//
//     var initialize_events = function() {
//
//     };
//
//     var update = function () {
//
//     };
//
// });




jQuery(document).ready(function ($) {
    "use strict";

    Tabs.initialize();
    Tickets.initialize();

});