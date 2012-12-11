
$.ajax({
    'async': true,
    'global': false,
    'url': "/ajax/glpi_stats",
    'dataType': "json",
    'success': function (json) {
        var conf = jQuery.parseJSON(json.conf);
        var chart;
        var data = {date: [], open: [], solved: [], stock: [], sla: []};
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

        $.each(json.glpi.sla, function(key, value) {
            data.sla.push(parseInt(value.total));
        })

        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'home-glpibar',
                type: 'column',
                alignTicks: false
            },
            title: null,
            xAxis: {
                categories: data.date
            },
            yAxis: [{
                min: 0,
                title: {
                    text: conf.home.glpibar.yaxis.tickets
                }
            },
            {
                min: 0,
                max: 100,
                title: {
                    text: conf.home.glpibar.yaxis.sla,
                    style: {
                        color: '#'+conf.home.glpibar.color.sla
                    }
                },
                gridLineWidth: 0,
                labels: {
                    style: {
                        color: '#'+conf.home.glpibar.color.sla
                    }
                },
                opposite: true,
                plotLines:
                [{
                    value: conf.home.glpibar.objectif,
                    width: 1,
                    color: '#'+conf.home.glpibar.color.objectif,
                    zIndex: 5
                }]
            },
            {
                min: 0,
                title: {
                    text: conf.home.glpibar.yaxis.total,
                    style: {
                        color: '#'+conf.home.glpibar.color.total
                    }
                },
                gridLineWidth: 0,
                labels: {
                    style: {
                        color: '#'+conf.home.glpibar.color.total
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
                    if (this.series.name != '% SLA') {
                        return ''+
                            this.x +': '+ this.y +' tickets';
                    } else {
                        return ''+
                            this.x +': '+ this.y +' %';
                    }
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            colors: [
                '#'+conf.home.glpibar.color.ouvert,
                '#'+conf.home.glpibar.color.ferme,
                '#'+conf.home.glpibar.color.total,
                '#'+conf.home.glpibar.color.sla
            ],
            series: [
                {
                    name: conf.home.glpibar.legend.ouvert,
                    data: data.open
                },
                {
                    name: conf.home.glpibar.legend.ferme,
                    data: data.solved
                },
                {
                    name: conf.home.glpibar.legend.total,
                    type: 'spline',
                    data: data.stock,
                    yAxis: 2
                },
                {
                    name: conf.home.glpibar.legend.sla,
                    type: 'spline',
                    data: data.sla,
                    yAxis: 1
                },
            ]
        });


    }
});
