var timer = 0,
column_count = 0,
tank_num = 0,
inverse_stats = [
    'row_aim_time',
    'row_accuracy'
],
current_tanks = [],
TURRETED_ONLY = 1,
NON_TURRETED_ONLY = 0,
BOTH = -1;

incrementTimer = function()
{
    $('#counter').text(timer);
    timer++;
}

compareStatsStandard = function(row)
{
    var values = $.map($(row).find('td'), function(elem, index){
        return $(elem).text() || null;
    });
    var max_value = Math.max.apply(Math, values);
    var min_value = Math.min.apply(Math, values);

    var colors = getComparisonColors(row.id);

    $(row).find('td').each(function(index, elem){
        var stat = $(this).text();
        if(stat == max_value || stat == 'Yes'){
            $(this).css('color', colors.max);
        } else if (stat == min_value || stat == 'No'){
            $(this).css('color', colors.min);
        } else {
            $(this).css('color', 'black');
        }
    });
}

compareStatsTriplet = function(row)
{
    var values = [[], [], []];
    $.each($(row).find('td'), function(index, elem){
        var stats = $(elem).text().split('/');
        if(stats[0])values[0].push(stats[0]);
        if(stats[1])values[1].push(stats[1]);
        if(stats[2])values[2].push(stats[2]);
    });
    var max_values = [], min_values = [];
    for(var i=0; i<3; i++){
        max_values[i] = Math.max.apply(Math, values[i]);
        min_values[i] = Math.min.apply(Math, values[i]);
    }
    if(isNaN(max_values[1])) return;

    $(row).find('td').each(function(index, elem){
        var stats = $(elem).find('span');
        if (stats.length === 0) return;
        for(var i=0; i<3; i++){
            if(stats[i].textContent == max_values[i]){
                $(stats[i]).css('color', 'green');
            } else if (stats[i].textContent == min_values[i]){
                $(stats[i]).css('color', 'red');
            } else {
                $(stats[i]).css('color', 'black');
            }
        }
    });
}

compareStatsDuo = function(row)
{
    var values = [[], []];
    $.each($(row).find('td'), function(index, elem){
        var stats = $(elem).text().split('/');
        if(stats[0])values[0].push(stats[0]);
        if(stats[1])values[1].push(stats[1]);
    });
    var max_values = [], min_values = [];
    for(var i=0; i<2; i++){
        max_values[i] = Math.max.apply(Math, values[i]);
        min_values[i] = Math.min.apply(Math, values[i]);
    }
    if(isNaN(max_values[1])) return;

    $(row).find('td').each(function(index, elem){
        var stats = $(elem).find('span');
        if (stats.length === 0) return;
        if(stats[0].textContent == max_values[0]){
            $(stats[0]).css('color', 'red');
        } else if (stats[0].textContent == min_values[0]){
            $(stats[0]).css('color', 'green');
        } else {
            $(stats[0]).css('color', 'black');
        }
        if(stats[1].textContent == max_values[1]){
            $(stats[1]).css('color', 'green');
        } else if (stats[1].textContent == min_values[1]){
            $(stats[1]).css('color', 'red');
        } else {
            $(stats[1]).css('color', 'black');
        }
    });
}

compareAll = function()
{
    $("#comparison_table tr").each(function(){
        if( $($(this).find('td')[0]).text().match(/[\d]+\/[\d]+\/[\d]+/) ){
            compareStatsTriplet(this);
        } else if($($(this).find('td')[0]).text().match(/[\d]+\/[\d]+/)){
            compareStatsDuo(this);
        } else {
            compareStatsStandard(this);
        }
    });
}

getComparisonColors = function(id)
{
    var colors = {
        max : 'green',
        min : 'red'
    };
    if($.inArray(id, inverse_stats) !== -1){
        colors.max = 'red';
        colors.min = 'green';
    }
    return colors;
}

loadTankStats = function(tank_data)
{
    updateCell('row_name', tank_data['name']);
    updateCell('row_nation', tank_data['nation']);
    updateCell('row_tier', tank_data['tier']);
    updateCell('row_class', tank_data['class']);
    updateCell('row_health', tank_data['hp_elite']);
    updateCell('row_speed_limit', tank_data['speed_limit']);
    updateCell('row_pivot', tank_data['pivot'] == 1 ? 'Yes' : 'No');
    var hull_armor = formatTriplet([
        tank_data['armor_front'],
        tank_data['armor_side'],
        tank_data['armor_rear']
    ]);
    updateCell('row_hull_armor', hull_armor);
    var gun = tank_data['guns'].pop();
    var turret = tank_data['turrets'].slice(-1).pop();
    var engine = tank_data['engines'].pop();
    var suspension = tank_data['suspensions'].pop();
    var radio = tank_data['radios'].pop();
    var turret_weight;
    if(tank_data['turrets'].length > 0){
        loadTurretStats(turret);
        turret_weight = turret['weight'];
        updateCell('row_gun_arc', '360');
        updateCell('row_gun_traverse', '--');
    } else {
        updateCell('row_view_range', tank_data['view_range']);
        loadBlankTurretStats();
        turret_weight = 0;
        var gun_arc = formatDuo([
            tank_data.gun_arc_left,
            tank_data.gun_arc_right
        ]);
        updateCell('row_gun_arc', gun_arc);
        updateCell('row_gun_traverse', tank_data['gun_traverse_speed']);
        $('.non_turreted_field').show();
    }
    tank_data.weight = (parseInt(gun['weight'])
        +parseInt(turret_weight)
        +parseInt(suspension['weight'])
        +parseInt(engine['weight'])
        +parseInt(radio['weight'])
        +parseInt(tank_data['chassis_weight']))/1000;
    updateCell('row_weight', tank_data.weight.toFixed(2));

    loadGunStats(gun);
    loadEngineStats(engine, tank_data.weight);
    loadSuspensionStats(suspension);
    loadRadioStats(radio);
}

loadGunStats = function(gun)
{
    updateCell('row_gun', gun['name']);
    updateCell('row_rate_of_fire', gun['rate_of_fire']);
    updateCell('row_pen_ap', gun['pen_ap']);
    updateCell('row_dmg_ap', gun['damage_ap']);
    updateCell('row_pen_he', gun['pen_he']);
    updateCell('row_dmg_he', gun['damage_he']);
    updateCell('row_pen_gold', gun['pen_gold']);
    updateCell('row_dmg_gold', gun['damage_gold']);
    updateCell('row_accuracy', gun['accuracy']);
    updateCell('row_aim_time', gun['aim_time']);
    var ap_dps = (gun['rate_of_fire'] * gun['damage_ap'] / 60).toFixed(2);
    var he_dps = (gun['rate_of_fire'] * gun['damage_he'] / 60).toFixed(2);
    var gold_dps = (gun['rate_of_fire'] * gun['damage_gold'] / 60).toFixed(2);
    updateCell('row_ap_dps', ap_dps);
    updateCell('row_he_dps', he_dps);
    updateCell('row_gold_dps', gold_dps);
    var gun_elevation = formatDuo([
        gun.depression,
        gun.elevation
    ]);
    updateCell('row_gun_elevation', gun_elevation);
    updateCell('row_ammo', gun.ammo);
}

loadTurretStats = function(turret)
{
    var turret_armor = formatTriplet([
        turret['armor_front'],
        turret['armor_side'],
        turret['armor_rear']
    ]);
    updateCell('row_turret_armor', turret_armor);
    updateCell('row_turret_traverse', turret['traverse_speed']);
    updateCell('row_view_range', turret['view_range']);
}

loadBlankTurretStats = function()
{
    updateCell('row_turret_armor', '--');
    updateCell('row_turret_traverse', '--');
}

loadEngineStats = function(engine, weight)
{
    updateCell('row_horsepower', engine['power']);
    var hp_per_ton = (parseInt(engine['power'])/weight).toFixed(2);
    updateCell('row_hp_per_ton', hp_per_ton);
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
    $('#'+row_id).find('.tank_'+tank_num).html(value);
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

turretCheck = function()
{
    var hasNonTurreted = false;
    var hasTurreted = false;
    $.each(current_tanks, function(i, tank){
        if(tank.turrets.length == 0){
            hasNonTurreted = true;
        } else {
            hasTurreted = true;
        }
    });
    if(hasNonTurreted && hasTurreted){
        return BOTH;
    } else {
        return hasNonTurreted ? NON_TURRETED_ONLY : TURRETED_ONLY;
    }
}

formatDuo = function(values)
{
    return '<span>'+values[0]+'</span>'+
        '/'+'<span>'+values[1]+'</span>';
}

formatTriplet = function(values)
{
    return '<span>'+values[0]+'</span>'+
        '/'+'<span>'+values[1]+'</span>'+
        '/'+'<span>'+values[2]+'</span>';
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
           current_tanks[tank_num] = tank_data;
           compareAll();
           switch(turretCheck()){
               case BOTH:
                   $('.non_turreted_field').show();
                   $('.turreted_field').show();
                   break;
               case TURRETED_ONLY:
                   $('.turreted_field').show();
                   $('.non_turreted_field').hide();
                   break;
               case NON_TURRETED_ONLY:
                   $('.non_turreted_field').show();
                   $('.turreted_field').hide();
                   break;
               default:
           }
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