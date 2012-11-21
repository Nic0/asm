/**
 * Mise à jour des données avec AJAX
 */

function ajax_update_glpi (config) {
    setInterval(function() {
        $('#glpi').load('/glpi/update', update_time());
    }, config.js.glpi.interval * 1000);
}

function ajax_update_zabbix (config) {
    setInterval(function() {
        $('#zabbix').load('/zabbix/update', update_time());
    }, config.js.zabbix.interval * 1000);
}

function ajax_update_badpasswd (config) {
    setInterval(function() {
        $('#badpasswd').load('/badpasswd/update', update_time());
    }, config.js.badpasswd.interval * 1000);
}

function update_time () {
    var currentTime = new Date()
    $('a[name=update]').html(('0' + currentTime.getHours()).slice(-2) + ':' +
                             ('0' + currentTime.getMinutes()).slice(-2));
}


function do_update (config) {
    ajax_update_glpi(config);
    ajax_update_zabbix(config);
    ajax_update_badpasswd(config);
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
            do_update(json);
        }
    });
    return json;
    })();
}

function setup_layout () {
    var layout = $('#container').layout({
        north: { resizable: false },
        west: {size: 600},
        stateManagement__enabled:   true,
        cookie__expires: 999,
        cookie__path: '/',
        cookie__autoSave: true
    });
}

function setup_config_tabs () {
    $(function() {
        $( "#tabs" ).tabs({
            beforeLoad: function( event, ui ) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                        "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                        "If this wouldn't be a demo." );
                });
            }
        });
    });
}


jQuery(document).ready(function($) {
    ajax_config();
    setup_layout();
    setup_config_tabs();
    $('[name="username"]').focus();






        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });

        var chart;
        var last = {
            down: {
                'x': null, 'y': null
            },
            up: {
                'x': null, 'y': null
            }
        };

        var point;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'snmp',
                type: 'spline',
                marginRight: 10,
                events: {
                    load: function() {

                        // set up the updating of the chart each second
                        var download = this.series[0];
                        var upload = this.series[1];
                        setInterval(function() {
                            $.getJSON('/snmp', function(data) {

                                var x = (new Date()).getTime(); // current time
                                var point = {};
                                if (last.down.y == null) {
                                    point.down = 0;
                                    point.up = 0;
                                } else {
                                    point.down = ((data.down - last.down.y) / 1024)/ ((x - last.down.x)/1000);
                                    point.up = ((data.up - last.up.y) / 1024)/ ((x - last.up.x)/1000);
                                }



                                last.down.y = parseInt(data.down);
                                last.up.y = parseInt(data.up);
                                last.down.x = x;
                                last.up.x = x;

                                download.addPoint([x, point.down], true, true);
                                upload.addPoint([x, point.up], true, true);
                            });


                        }, 5000);
                    }
                }
            },
            // plotOptions: {
            //     spline: {
            //         color: '#666666'
            //     }
            // },
            title: {
                text: '10.0.72.1'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: 'Mo/s'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                max: 1500,
                min: 0
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

                        for (i = -19; i <= 0; i++) {
                            data.push({
                                x: time + i * 1000,
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

                        for (i = -19; i <= 0; i++) {
                            data.push({
                                x: time + i * 1000,
                                y: 0
                            });
                        }
                        return data;
                    })()
                },
            ]
        });



});