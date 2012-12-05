function plot_snmp_graph (conf) {

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    var last = {
        "adista": {
            down: {
                'x': null, 'y': null
            },
            up: {
                'x': null, 'y': null
            }
        }
    };

    var point;

    var options = {
        chart: {
            renderTo: 'home-adista',
            type: 'spline',
            marginRight: 10,
            events: {
                load: function() {
                    // set up the updating of the chart each second
                    var download = this.series[0];
                    var upload = this.series[1];
                    setInterval(function() {
                        $.getJSON('/snmp_adista', function(data) {

                            var x = (new Date()).getTime(); // current time
                            var point = {};
                            if (last.adista.down.y == null) {
                                point.down = 0;
                                point.up = 0;
                            } else {
                                point.down = ((data.down - last.adista.down.y))/ ((x - last.adista.down.x)/1000);
                                point.up = ((data.up - last.adista.up.y))/ ((x - last.adista.up.x)/1000);
                            }



                            last.adista.down.y = parseInt(data.down);
                            last.adista.up.y = parseInt(data.up);
                            last.adista.down.x = x;
                            last.adista.up.x = x;

                            download.addPoint([x, point.down], true, true);
                            upload.addPoint([x, point.up], true, true);
                        });


                    }, conf.home.snmp.update * 1000);
                }
            }
        },
        title: {
            text: null
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'b/s'
            },
            plotLines: [
            {
                value: conf.home.snmp.warning.level * 1000000,
                width: 1,
                color: '#' + conf.home.snmp.warning.color,
                zIndex: 5
            },
            {
                value: conf.home.snmp.error.level * 1000000,
                width: 1,
                color: '#' + conf.home.snmp.error.color,
                zIndex: 5
            }],
            max: 100000000,
            min: 0,
            gridLineColor: '#DDDDDD'
        },
        plotOptions: {
            series: {
                pointInterval: 10
            }
        },
        tooltip: {
            formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                    Highcharts.numberFormat(this.y, 2);
            }
        },
        legend: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        colors: ['#0091FE', '#65FF00'],
        series: [
            {
                name: 'Download',
                data: (function() {
                    // generate an array of random data
                    var data = [];
                        $.ajax({
                            'async': false,
                            'global': false,
                            'url': "/ajax/init_snmp",
                            'dataType': "json",
                            'success': function (json) {
                                var time = (new Date()).getTime();
                                var item = json.down;
                                for (i = -40; i < 0; i++) {
                                    data.push({
                                        x: time + i*5 * 1000,
                                        y: parseInt( (item[i+41].value - item[i+40].value) / 5 )
                                    });
                                }

                            }
                        });
                    return data;
                })()
            },
            {
                name: 'Upload',
                data: (function() {
                    // generate an array of random data
                    var data = [];
                        $.ajax({
                            'async': false,
                            'global': false,
                            'url': "/ajax/init_snmp",
                            'dataType': "json",
                            'success': function (json) {
                                var time = (new Date()).getTime();
                                var item = json.up;
                                for (i = -40; i < 0; i++) {
                                    data.push({
                                        x: time + i*5 * 1000,
                                        y: parseInt( (item[i+41].value - item[i+40].value) / 5 )
                                    });
                                }
                            }
                        });
                    return data;
                })()
            }
        ]
    };


    var adista = new Highcharts.Chart(options);
}

function plot_glpi_columns_graph () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'home-glpibar',
                type: 'column'
            },
            title: null,
            xAxis: {
                categories: (function() {
                    // generate an array of random data
                    var data = [];
                        $.ajax({
                            'async': false,
                            'global': false,
                            'url': "/ajax/glpi_stats",
                            'dataType': "json",
                            'success': function (json) {
                                var item = json.glpi;
                                for (i = 0; i < 20; i++) {
                                    data.push(
                                        item[i].date
                                    );
                                }

                            }
                        });
                    return data;
                })()
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Nombre de tickets'
                }
            },
            legend: false,
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
                series: [{
                name: 'Tickets Ouvert',
                data: (function() {
                    // generate an array of random data
                    var data = [];
                        $.ajax({
                            'async': false,
                            'global': false,
                            'url': "/ajax/glpi_stats",
                            'dataType': "json",
                            'success': function (json) {
                                var item = json.glpi;
                                for (i = 0; i < 20; i++) {
                                    data.push(
                                        parseInt(item[i].total)
                                    );
                                }

                            }
                        });
                    return data;
                })()
            }]
        });
    });

};

function ajax_config () {
    var json = (function () {
    $.ajax({
        'async': false,
        'global': false,
        'url': "/config/config.json",
        'dataType': "json",
        'success': function (data) {
            plot_snmp_graph(data);
            plot_glpi_columns_graph();
        }
    });
    })();
}

jQuery(document).ready(function($) {
    ajax_config();
});