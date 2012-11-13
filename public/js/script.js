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

function update_time () {
    var currentTime = new Date()
    $('a[name=update]').html(currentTime.getHours() + ':' + currentTime.getMinutes());
}


function do_update (config) {
    ajax_update_glpi(config);
    ajax_update_zabbix(config);
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


jQuery(document).ready(function($) {
    ajax_config();

    var layout = $('#container').layout({
        north: {
            closable: true,
            resizable: false
        },
        stateManagement__enabled:   true,
        cookie: {expires: "999"}
    });
});