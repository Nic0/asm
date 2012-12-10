var chart;
var data = {date: [], open: [], solved: [], stock: []};

$.ajax({
    'async': false,
    'global': false,
    'url': "/ajax/glpi_stats",
    'dataType': "json",
    'success': function (json) {

        $.each(json.glpi.open, function(key, value) {
            data.date.push(value.date);
            data.open.push(parseInt(value.total));
        })

        $.each(json.glpi.solved, function(key, value) {
            data.solved.push(parseInt(value.total));
        })

        $.each(json.glpi.stock, function(key, value) {
            data.stock.push(parseInt(value.total));
        })


    }
});

chart = new Highcharts.Chart({
    chart: {
        renderTo: 'home-glpibar',
        type: 'column'
    },
    title: null,
    xAxis: {
        categories: data.date
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'Nombre de tickets'
        }
    },
    {
        min: 0,
        title: {
            text: 'Total Tickets Ouverts',
            style: {
                color: '#89A54E'
            }
        },
        labels: {
            style: {
                color: '#89A54E'
            }
        },
        opposite: true
    }],
    legend: {
        layout: 'vertical',
        backgroundColor: '#FFFFFF',
        align: 'left',
        verticalAlign: 'top',
        x: 70,
        y: 10,
        floating: true,
        shadow: true
    },
    tooltip: {
        formatter: function() {
            return ''+
                this.x +': '+ this.y +' tickets';
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    colors: ['#3465a4'],
    series: [
        {
            name: 'Tickets Ouverts / Journée',
            data: data.open
        },
        {
            name: 'Tickets Résolus / Journée',
            data: data.solved
        },
        {
            name: 'Total Ouverts',
            type: 'spline',
            data: data.stock,
            yAxis: 1
        },
    ]
});