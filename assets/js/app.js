var App = (function(module, $, window, globals) {
    "use strict";

    var tabs;

    var find_tab = function (id) {
        var tab = false;

        tabs.find("li").each(function (index, element) {
            if ($(element).data("id") === id) {
                tab = index;
            }
        });

        return tab;
    };

    var close_tab = function (e) {
        var tab = $(e.target).closest("li").remove().attr("aria-controls");

        $("#" + tab).remove();
        tabs.tabs("refresh");
    };

    var open_tab = function (data) {
        if (!find_tab(data.id)) {
            var li = $("<li><a href=\"#" + data.id + "\">" + data.title + "</a><span class=\"ui-icon-close close-tab icon-cross\"></span></li>");
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

        $("body").on("click", "span.close-tab", close_tab);
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



