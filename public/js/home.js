function plot_snmp_graph (conf) {

    var data = {up: [], down: []};
    $.ajax({
        'async': true,
        'global': false,
        'url': "/ajax/init_snmp",
        'dataType': "json",
        'success': function (json) {
            var time = (new Date()).getTime();
            for (i = -40; i < 0; i++) {
                data.up.push({
                    x: time + i*5 * 1000,
                    y: parseInt( (json.up[i+41].value - json.up[i+40].value) / 5 )
                });
                data.down.push({
                    x: time + i*5 * 1000,
                    y: parseInt( (json.down[i+41].value - json.down[i+40].value) / 5 )
                });
            }
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
                        data: data.down
                    },
                    {
                        name: 'Upload',
                        data: data.up
                    }
                ]
            };


            var adista = new Highcharts.Chart(options);
        }
    });
}

function ajaxify_load (conf) {
    setInterval(function() {
        $('#zabbix').load('/ajax/zabbix_home');
    }, conf.home.zabbix.update * 1000);

    setInterval(function() {
        $('#glpipie').load('/ajax/glpipie_home');
    }, conf.home.glpipie.update * 1000);

    setInterval(function() {
        $('#glpibar').load('/ajax/glpibar_home');
    }, conf.home.glpibar.update * 1000);
}

function ajax_config () {
    var json = (function () {
    $.ajax({
        'async': true,
        'global': false,
        'url': "/config/config.json",
        'dataType': "json",
        'success': function (data) {
            plot_snmp_graph(data);
            ajaxify_load(data);
        }
    });
    })();
}

function confirm_delete () {
    var r=confirm("Supprimer l'élément ?");
    return r;
}

//jQuery(document).ready(function($) {
    ajax_config();
//});