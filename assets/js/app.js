var App = (function(module, $, window, globals) {
    "use strict";

    var tabs;

    var close_tab = function () {

    };

    var open_tab = function (data) {
        var existing = false;

        tabs.find("li").each(function (index, element) {
            if ($(element).data("id") === data.id) {
                existing = index;
            }
        });

        if (!existing) {
            var li = $("<li><a href=\"#" + data.id + "\">" + data.title + "</a><i class=\"ui-icon-close icon-cross\"></i></li>");
            var panel = $($.parseHTML("<div id=\"" + data.id + "\"></div>"));

            li.data("id", data.id);
            panel.html(data.content);

            tabs.find("ul").append(li);
            tabs.append(panel);

            tabs.tabs("refresh");
            tabs.tabs("option", "active", li.index());
        } else {
            tabs.tabs("option", "active", existing);
        }
    };

    var initialize = function () {
        tabs = $("#tabs");
        tabs.tabs();
    };

    return {
        initialize: initialize,
        open_tab: open_tab
    };

})(App || {}, jQuery, window, Globals);


jQuery(document).ready(function ($) {
    "use strict";

    App.initialize();
    Tickets.initialize();

});



