jQuery(document).ready(function($) {


        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });

        var renater;
        var adista;
        var last = {
            "renaster": {
                down: {
                    'x': null, 'y': null
                },
                up: {
                    'x': null, 'y': null
                }
            },
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
                renderTo: 'renater',
                type: 'spline',
                marginRight: 10,
                events: {
                    load: function() {

                        // set up the updating of the chart each second
                        var download = this.series[0];
                        var upload = this.series[1];
                        setInterval(function() {
                            $.getJSON('/snmp_renater', function(data) {

                                var x = (new Date()).getTime(); // current time
                                var point = {};
                                if (last.renaster.down.y == null) {
                                    point.down = 0;
                                    point.up = 0;
                                } else {
                                    point.down = ((data.down - last.renaster.down.y))/ ((x - last.renaster.down.x)/1000);
                                    point.up = ((data.up - last.renaster.up.y))/ ((x - last.renaster.up.x)/1000);
                                }



                                last.renaster.down.y = parseInt(data.down);
                                last.renaster.up.y = parseInt(data.up);
                                last.renaster.down.x = x;
                                last.renaster.up.x = x;

                                download.addPoint([x, point.down], true, true);
                                upload.addPoint([x, point.up], true, true);
                            });


                        }, 5000);
                    }
                }
            },
            title: {
                text: 'Renater'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: 'b/s'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                tickPixelInterval: 30,
                max: 100000000,
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

                        for (i = -19; i <= 0; i++) {
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

        renater = new Highcharts.Chart({
            chart: {
                renderTo: 'adista',
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


                        }, 5000);
                    }
                }
            },
            title: {
                text: 'Adista'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: 'b/s'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                tickPixelInterval: 30,
                max: 100000000,
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

                        for (i = -19; i <= 0; i++) {
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


});