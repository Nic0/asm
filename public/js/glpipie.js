$.ajax({
    'async': false,
    'global': false,
    'url': "/ajax/glpi_type",
    'dataType': "json",
    'success': function (json) {

        var chart;

        var colors = Highcharts.getOptions().colors,
            categories = ['Incidents', 'Demandes'],
            name = 'Tickets GLPI';

        var incident_categories = [];
        var incident_data = [];
        var demande_categories = [];
        var demande_data = [];

        $.each(json.glpi.incident, function(key, value) {
            if (key!='total') {
                incident_categories.push(key);
                incident_data.push(parseInt(value));
            }
        });

        $.each(json.glpi.demande, function(key, value) {
            if (key != 'total') {
                demande_categories.push(key);
                demande_data.push(parseInt(value));
            }
        });

        data = [{
                y: json.glpi.incident.total,
                //color: '#0091FE',
                color: '#c17d11',
                //color: colors[0],
                drilldown: {
                    name: 'Types d\'incidents',
                    categories: incident_categories,
                    data: incident_data,
                    color: colors[0]
                }
            }, {
                y: json.glpi.demande.total,
                //color: '#65FF00',
                color: '#75507b',
                //color: colors[1],
                drilldown: {
                    name: 'Types d\'incidents',
                    categories: demande_categories,
                    data: demande_data,
                    color: colors[1]
                }
            }
        ];

        // Build the data arrays
        var browserData = [];
        var versionsData = [];
        for (var i = 0; i < data.length; i++) {

            // add browser data
            browserData.push({
                name: categories[i],
                y: data[i].y,
                color: data[i].color
            });

            // add version data
            for (var j = 0; j < data[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
                versionsData.push({
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: Highcharts.Color(data[i].color).brighten(brightness).get()
                });
            }
        }

        // Create the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'home-glpipie',
                type: 'pie'
            },
            title: {
                text: null
            },

            plotOptions: {
                pie: {
                    shadow: true
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            series: [{
                name: 'Tickets',
                data: browserData,
                size: '60%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 5 ? this.point.name : null;
                    },
                    color: 'white',
                    distance: -30
                }
            }, {
                name: 'Tickets',
                data: versionsData,
                innerSize: '60%',
                dataLabels: {
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ this.y  : null;
                    }
                }
            }]
        });
    }
});
