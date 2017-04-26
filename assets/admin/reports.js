jQuery(document).ready(function ($) {

    var start_date = $('.start_date').datepicker({ dateFormat : 'dd-mm-yy' }).datepicker('setDate', '-7d');
    var end_date = $('.end_date').datepicker({ dateFormat : 'dd-mm-yy' }).datepicker('setDate', '0');

    $('.date-range-select').change(function (e) {

        var selection = $(e.target).val();
        var d = new Date();

        switch($(e.target).val()) {
            case 'last_week':
                start_date.datepicker('setDate', '-7d');
                break;

            case 'this_month':
                start_date.datepicker('setDate', new Date(d.getFullYear(), d.getMonth(), 1));
                break;

            case 'last_month':
                start_date.datepicker('setDate', '-1m');
                break;

            case 'this_year':
                start_date.datepicker('setDate', new Date(d.getFullYear(), 0, 1));
                break;

            case 'custom':
                start_date.datepicker('setDate', d);
        }

        $('.date-range').toggleClass('hidden', selection !== 'custom');

    });

    new Chartist.Line('#stats-chart', {
        labels: [1, 2, 3, 4],
        series: [[100, 120, 180, 200]]
    });
});