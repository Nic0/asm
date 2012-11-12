/**
 * Mise à jour des données avec AJAX
 */

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

/**
 * Gestion du drag n drop
 */

    $(function() {
        $( ".column" ).sortable({
            connectWith: ".column"
        });

        $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
            .find( ".portlet-header" )
                .addClass( "ui-widget-header ui-corner-all" )
                .prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
                .end()
            .find( ".portlet-content" );

        $( ".portlet-header .ui-icon" ).click(function() {
            $( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
            $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
        });

        $( ".column" ).disableSelection();
    });