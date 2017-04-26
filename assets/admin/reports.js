jQuery(document).ready(function ($) {

    var start_date = $('.start_date').datepicker({ dateFormat : 'dd-mm-yy' }).datepicker('setDate', '-7d');
    var end_date = $('.end_date').datepicker({ dateFormat : 'dd-mm-yy' }).datepicker('setDate', '0');

    $('.date-range-select').change(function (e) {

        var selection = $(e.target).val();
        var d = moment();

        switch($(e.target).val()) {
            case 'last_week':
                start_date.datepicker('setDate', '-7d');
                end_date.datepicker('setDate',  d.format('DD-MM-YYYY'));
                break;

            case 'this_month':
                end_date.datepicker('setDate',  d.format('DD-MM-YYYY'));
                start_date.datepicker('setDate', d.startOf('month').format('DD-MM-YYYY'));
                break;

            case 'last_month':
                d = d.subtract(1, 'month');

                start_date.datepicker('setDate', d.startOf('month').format('DD-MM-YYYY'));
                end_date.datepicker('setDate',  d.endOf('month').format('DD-MM-YYYY'));
                break;

            case 'this_year':
                d = d.subtract(1, 'year');

                start_date.datepicker('setDate', d.startOf('year').format('DD-MM-YYYY'));
                end_date.datepicker('setDate',  d.endOf('year').format('DD-MM-YYYY'));
                break;

            case 'custom':
                start_date.datepicker('setDate', d.format('DD-MM-YYYY'));
                end_date.datepicker('setDate', d.format('DD-MM-YYYY'));
        }

        $('.date-range').toggleClass('hidden', selection !== 'custom');

    });

    new Chartist.Line('#stats-chart', {
        labels: [1, 2, 3, 4],
        series: [[100, 120, 180, 200]]
    });
});