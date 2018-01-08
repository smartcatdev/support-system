/**
 * @summary Module for extending core JS prototypes.
 *
 * @since 1.6.0
 */
;var ucare = (function (exports) {
    "use strict";

    exports.ext = exports.ext || {};

    /**
     * @summary Deep compare two objects to see if they are equal.
     *
     * @param {*} obj1
     * @param {*} obj2
     *
     * @since 1.6.0
     * @return {boolean}
     */
    exports.ext.compare = function (obj1, obj2) {

        // Loop through properties in object 1
        for (var p in obj1) {

            // Check property exists on both objects
            if (obj1.hasOwnProperty(p) !== obj2.hasOwnProperty(p))
                return false;

            switch (typeof (obj1[p])) {
                // Deep compare objects
                case 'object':
                    if (!Object.compare(obj1[p], obj2[p]))
                        return false;

                    break;

                // Compare function code
                case 'function':
                    if (typeof (obj2[p]) == 'undefined' || (p != 'compare' && obj1[p].toString() != obj2[p].toString()))
                        return false;

                    break;


                // Compare values
                default:
                    if (obj1[p] != obj2[p])
                        return false;

                    break;
            }
        }

        // Check object 2 for any extra properties
        for (var p in obj2) {
            if (typeof (obj1[p]) == 'undefined')
                return false;
        }

        return true;
    };

    return exports;

})(ucare || {});
