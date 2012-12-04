function plot_graph (conf) {
        Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    var adista;
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

    renater = new Highcharts.Chart({
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
        series: [
            {
                name: 'Download',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;

                    for (i = -29; i <= 0; i++) {
                        data.push({
                            x: time + i*5 * 1000,
                            y: 0
                        });
                    }
                    return data;
                })()
            },
            {
                name: 'Upload',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;

                    for (i = -29; i <= 0; i++) {
                        data.push({
                            x: time + i*5 * 1000,
                            y: 0
                        });
                    }
                    return data;
                })()
            },
        ]
    });
}

function ajax_config () {
    var json = (function () {
    var json = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': "/config/config.json",
        'dataType': "json",
        'success': function (data) {
            json = data;
            plot_graph(json);
        }
    });
    return json;
    })();
}

jQuery(document).ready(function($) {
    ajax_config();
});