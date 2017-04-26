jQuery(document).ready(function ($) {
    $('input.date').datepicker({ dateFormat : 'dd-mm-yy' }).datepicker('setDate', '0');

    $('.date-range-select').change(function (e) {

        $('.date-range').toggleClass('hidden', $(e.target).val() !== 'custom');

    });
});