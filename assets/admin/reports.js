jQuery(document).ready(function ($) {
    $('input.date').datepicker({ dateFormat : 'dd-mm-yy' }).datepicker('setDate', '0');

    $('.date-range-select').change(function (e) {

        $('.date-range').toggleClass('hidden', $(e.target).val() !== 'custom');

    });


    new Chartist.Line('#stats-chart', {
        labels: [1, 2, 3, 4],
        series: [[100, 120, 180, 200]]
    });
});