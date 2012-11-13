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



        // init the Sortables
        $(".column").sortable({
            connectWith:    $(".column")
        ,   placeholder:    'widget-placeholder'
        ,   cursor:         'move'
        //  use a helper-clone that is append to 'body' so is not 'contained' by a pane
        ,   helper:         function (evt, ui) { return $(ui).clone().appendTo('body').show(); }
        ,   over:           function (evt, ui) {
                                var
                                    $target_UL  = $(ui.placeholder).parent()
                                ,   targetWidth = $target_UL.width()
                                ,   helperWidth = ui.helper.width()
                                ,   padding     = parseInt( ui.helper.css('paddingLeft') )
                                                + parseInt( ui.helper.css('paddingRight') )
                                                + parseInt( ui.helper.css('borderLeftWidth') )
                                                + parseInt( ui.helper.css('borderRightWidth') )
                                ;
                                //if (( (helperWidth + padding) - targetWidth ) > 20)
                                    ui.helper
                                        .height('auto')
                                        .width( targetWidth - padding )
                                    ;
                            }
        });
/*
        $("#draggable").draggable({
        //  use a helper-clone that is append to 'body' so is not 'contained' by a pane
            helper: function () { return $(this).clone().appendTo('body').css('zIndex',5).show(); }
        ,   cursor: 'move'
        });

        $('#droppable').droppable({
           accept:  '#draggable'
        ,  drop:    function () { alert('The Draggable was Dropped!'); }
        });
*/






});

