function ajax_update_glpi (config) {
    setInterval(function() {
        $('#glpi').load('/glpi/update');
    }, config.js.glpi.interval * 1000);
}

function ajax_update_zabbix (config) {
    setInterval(function() {
        $('#zabbix').load('/zabbix/update');
    }, config.js.zabbix.interval * 1000);
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
});