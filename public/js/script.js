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
    $('a[name=update]').html(('0' + currentTime.getHours()).slice(-2) + ':' +
                             ('0' + currentTime.getMinutes()).slice(-2));
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

function setup_layout () {
    var layout = $('#container').layout({
        north: { resizable: false },
        stateManagement__enabled:   true,
        cookie__expires: 999,
        cookie__path: '/',
        cookie__autoSave: false
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
});