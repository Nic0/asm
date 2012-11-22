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
    setup_layout();
    setup_config_tabs();
    $('[name="username"]').focus();

    $('.host').change(function() {
        var hostid = $('.host').val();
        $('.item').load('/state/ajax_zabbix_host', { 'hostid': hostid }, function  (data) {
            // body...
        });
    });

    $('.item').change(function() {
        // do something... or not
    });
});