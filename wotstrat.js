var timer = 0;
var column_count = 0;
var tank_num = 0;

incrementTimer = function()
{
    $('#counter').text(timer);
    timer++;
}

compareStats = function(row)
{
    var values = $.map($(row).find('td'), function(elem, index){
        return $(elem).text();
    });
    var max_value = Math.max.apply(Math, values);
    var min_value = Math.min.apply(Math, values);
    $(row).find('td').each(function(index, elem){
        if($(this).text() == max_value){
            $(this).css('color', 'green');
        } else if ($(this).text() == min_value){
            $(this).css('color', 'red');
        } else {
            $(this).css('color', 'black');
        }
    });
}

compareAll = function()
{
    $("#comparison_table tr").each(function(){
        compareStats(this);
    });
}

loadTankStats = function(tank_data)
{
    updateCell('row_name', tank_data['name']);
    updateCell('row_nation', tank_data['nation']);
    updateCell('row_tier', tank_data['tier']);
    updateCell('row_class', tank_data['class']);
    updateCell('row_health', tank_data['hp_elite']);
    updateCell('row_speed_limit', tank_data['speed_limit']);
    var hull_armor = tank_data['armor_front']+'/'+tank_data['armor_side']+'/'+tank_data['armor_rear'];
    updateCell('row_hull_armor', hull_armor);
    var gun = tank_data['guns'].pop();
    var turret = tank_data['turrets'].slice(-1).pop();
    var engine = tank_data['engines'].pop();
    var suspension = tank_data['suspensions'].pop();
    var radio = tank_data['radios'].pop();
    var weight = (parseInt(gun['weight'])
        +parseInt(turret['weight'])
        +parseInt(suspension['weight'])
        +parseInt(engine['weight'])
        +parseInt(radio['weight'])
        +parseInt(tank_data['chassis_weight']))/1000;
    updateCell('row_weight', weight);
    var hp_per_ton = (parseInt(engine['power'])/weight).toFixed(2);
    updateCell('row_hp_per_ton', hp_per_ton);

    loadGunStats(gun);
    if(tank_data['turrets'].length > 0){
        loadTurretStats(turret);
    } else {
        updateCell('row_view_range', tank_data['view_range']);
        loadBlankTurretStats();
    }
    loadEngineStats(engine);
    loadSuspensionStats(suspension);
    loadRadioStats(radio);
}

loadGunStats = function(gun)
{
    updateCell('row_gun', gun['name']);
    var rof = gun['rate_of_fire'];
    updateCell('row_rate_of_fire', rof);
    updateCell('row_pen_ap', gun['pen_ap']);
    var dmg_ap = gun['damage_ap'];
    updateCell('row_dmg_ap', dmg_ap);
    updateCell('row_pen_he', gun['pen_he']);
    var dmg_he = gun['damage_he'];
    updateCell('row_dmg_he', gun['damage_he']);
    updateCell('row_accuracy', gun['accuracy']);
    updateCell('row_aim_time', gun['aim_time']);
    var ap_dps = (rof * dmg_ap / 60).toFixed(2);
    var he_dps = (rof * dmg_he / 60).toFixed(2);
    updateCell('row_ap_dps', ap_dps);
    updateCell('row_he_dps', he_dps);
}

loadTurretStats = function(turret)
{
    var turret_armor = turret['armor_front']+
        '/'+turret['armor_side']+
        '/'+turret['armor_rear'];
    updateCell('row_turret_armor', turret_armor);
    updateCell('row_turret_traverse', turret['traverse_speed']);
    updateCell('row_view_range', turret['view_range']);
}

loadBlankTurretStats = function()
{
    updateCell('row_turret_armor', '--');
    updateCell('row_turret_traverse', '--');
}

loadEngineStats = function(engine)
{
    updateCell('row_horsepower', engine['power']);
}

loadSuspensionStats = function(suspension)
{
    updateCell('row_traverse_speed', suspension['traverse_speed']);
}

loadRadioStats = function(radio)
{
    updateCell('row_signal_range', radio['range']);
}

updateCell = function(row_id, value)
{
    $('#'+row_id).find('.tank_'+tank_num).text(value);
}

appendColumn = function()
{
    $("#comparison_table tr").append("<td class='tank_"+column_count+"'></td>");
    column_count++;
}

submitFilters = function()
{
    var tank_select = $('#tank_select');
    tank_select.html('');
    var filter_nation = $('input[name=filter_nation]:checked').val();
    var filter_class = $('input[name=filter_class]:checked').val();
    var filter_tier = $('input[name=filter_tier]:checked').val();
    var data = {
        'action' : 'getTankOptions',
        'nation' : filter_nation,
        'class'  : filter_class,
        'tier'   : filter_tier
    }
    $.post('AjaxHandler.php', data, function(options){
        console.log(options);
        options = JSON.parse(options);

        $.each(options, function(index, item){
            tank_select.append($('<option></option>')
                .attr('value', item['id'])
                .text(item['name']));

        });
    });
}

$(function(){
    appendColumn();
    appendColumn();

    $('#scraper').on('click', function(){
        $('#content').html('');
        $('#loading').show();
        incrementTimer();
        var timer_set = setInterval(incrementTimer, 1000);
        $.post('main.php', function(data, status, jq){
            $('#loading').hide();
           $('#content').html(data);
           timer = 0;
            clearInterval(timer_set);
        });
    })
    
    $('body').on('click', '.load_tank', function(){
        var id;
        tank_num = $(this).index();
        if ($('#tank_select').val()){
            id = $('#tank_select').val();
        } else {
            id = Math.floor(Math.random()*290);
        }
        $.post('AjaxHandler.php', {action : 'loadTank', tank_id : id}, function(data, status, jq){
           var tank_data = JSON.parse(data);
           loadTankStats(tank_data);
           compareAll();
        });
    })

    $('#add_tank').on('click', function(){
        var num = column_count + 1;
        $('#button_container').append('<input type="button" class="load_tank" value="Load Tank '+num+'" />');
        appendColumn();
        if(column_count === 6) $(this).hide();
    })

    $('.tank_filter').on('click', function(){
        submitFilters();
    })

    $('#filter_nation_clear').on('click', function(){
        $('input[name=filter_nation]:checked').prop('checked', false);
        submitFilters();
    })

    $('#filter_tier_clear').on('click', function(){
        $('input[name=filter_tier]:checked').prop('checked', false);
        submitFilters();
    })

    $('#filter_class_clear').on('click', function(){
        $('input[name=filter_class]:checked').prop('checked', false);
        submitFilters();
    })
});