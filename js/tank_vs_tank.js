var column_count = 0,
tank_num = 0,
inverse_stats = [
    'row_aim_time',
    'row_accuracy'
],
current_tanks = [],
TURRETED_ONLY = 1,
NON_TURRETED_ONLY = 0,
BOTH = -1;

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
    updateCell('row_speed_limit', tank_data['speed_limit']);
    updateCell('row_pivot', tank_data['pivot'] == 1 ? 'Yes' : 'No');
    var hull_armor = formatTriplet([
        tank_data['armor_front'],
        tank_data['armor_side'],
        tank_data['armor_rear']
    ]);
    updateCell('row_hull_armor', hull_armor);
    tank_data.gun = getBestGun(tank_data['guns']);
    tank_data.turret = tank_data['turrets'].slice(-1).pop();
    tank_data.engine = tank_data['engines'].slice(-1).pop();
    tank_data.suspension = tank_data['suspensions'].slice(-1).pop();
    tank_data.radio = tank_data['radios'].slice(-1).pop();
    if(tank_data['turrets'].length > 0){
        loadTurretStats(tank_data.turret, 1);
        updateCell('row_gun_arc', '360');
        updateCell('row_gun_traverse', '--');
    } else {
        updateCell('row_view_range', tank_data['view_range']);
        updateCell('row_health', tank_data.hp_elite);
        loadBlankTurretStats();
        var gun_arc = formatDuo([
            tank_data.gun_arc_left,
            tank_data.gun_arc_right
        ]);
        updateCell('row_gun_arc', gun_arc);
        updateCell('row_gun_traverse', tank_data['gun_traverse_speed']);
        $('.non_turreted_field').show();
    }
    updateWeightStats();
    loadGunStats(tank_data.gun);
    loadEngineStats(tank_data.engine);
    loadSuspensionStats(tank_data.suspension);
    loadRadioStats(tank_data.radio);
}

calculateWeight = function()
{
    var tank_data = current_tanks[tank_num];
    var turret_weight = tank_data.turret ? tank_data.turret.weight : 0;
    tank_data.weight = (parseInt(tank_data.gun['weight'])
        +parseInt(turret_weight)
        +parseInt(tank_data.suspension['weight'])
        +parseInt(tank_data.engine['weight'])
        +parseInt(tank_data.radio['weight'])
        +parseInt(tank_data['chassis_weight']))/1000;
}

loadGunStats = function(gun)
{
    var current_tank = current_tanks[tank_num];
    current_tank.gun = gun;
    if(current_tank.turret && current_tank.turret.isElite){
        gun = getEliteVersion(gun);
    }
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
    updateWeightStats();
}

loadTurretStats = function(turret, isElite)
{
    var current_tank = current_tanks[tank_num];
    current_tank.turret = turret;
    current_tank.turret.isElite = isElite;
    var turret_armor = formatTriplet([
        turret['armor_front'],
        turret['armor_side'],
        turret['armor_rear']
    ]);
    updateCell('row_turret_armor', turret_armor);
    updateCell('row_turret_traverse', turret['traverse_speed']);
    updateCell('row_view_range', turret['view_range']);
    var health = isElite ? current_tank.hp_elite : current_tank.hp_stock;
    updateCell('row_health', health);
    updateWeightStats();
    loadGunStats(current_tank.gun);
}

loadBlankTurretStats = function()
{
    updateCell('row_turret_armor', '--');
    updateCell('row_turret_traverse', '--');
}

loadEngineStats = function(engine)
{
    var current_tank = current_tanks[tank_num];
    current_tank.engine = engine;
    updateCell('row_horsepower', engine['power']);
    updateWeightStats();
}

loadSuspensionStats = function(suspension)
{
    var current_tank = current_tanks[tank_num];
    current_tank.suspension = suspension;
    updateCell('row_traverse_speed', suspension['traverse_speed']);
    updateWeightStats();
}

loadRadioStats = function(radio)
{
    var current_tank = current_tanks[tank_num];
    current_tank.radio = radio;
    updateCell('row_signal_range', radio['range']);
    updateWeightStats();
}

updateWeightStats = function()
{
    var current_tank = current_tanks[tank_num];
    calculateWeight();
    updateCell('row_weight', current_tank.weight.toFixed(2));
    var hp_per_ton = (parseInt(current_tank.engine.power)/current_tank.weight).toFixed(2);
    updateCell('row_hp_per_ton', hp_per_ton);
}

updateCell = function(row_id, value)
{
    $('#'+row_id).find('.tank_'+tank_num).html(value);
}

appendColumn = function()
{
    $("#comparison_table tr:first").append(getFilters());
    $("#comparison_table tr:gt(0)").append("<td class='tank_"+column_count+"'></td>");
    if(!$('.module_selects :first').is(':hidden'))$('.module_selects').show();
    column_count++;
}

removeColumn = function()
{
    $("#comparison_table tr td:last-child").remove();
    current_tanks.pop();
    column_count--;
}

getFilters = function()
{
    var filters = '<td>'+
                    '<div class="filter_wrap">'+
                        '<div class="tank_filter nation usa" title="usa"></div>'+
                        '<div class="tank_filter nation germany" title="germany"></div>'+
                        '<div class="tank_filter nation ussr" title="ussr"></div>'+
                        '<div class="tank_filter nation france" title="france"></div>'+
                        '<div class="tank_filter nation china" title="china"></div>'+
                        '<div class="tank_filter nation uk" title="uk"></div>'+
                        '<br>'+
                        '<div class="tank_filter tier">I</div>'+
                        '<div class="tank_filter tier">II</div>'+
                        '<div class="tank_filter tier">III</div>'+
                        '<div class="tank_filter tier">IV</div>'+
                        '<div class="tank_filter tier">V</div>'+
                        '<div class="tank_filter tier">VI</div>'+
                        '<div class="tank_filter tier">VII</div>'+
                        '<div class="tank_filter tier">VIII</div>'+
                        '<div class="tank_filter tier">IX</div>'+
                        '<div class="tank_filter tier">X</div>'+
                        '<br>'+
                        '<div class="tank_filter class light" title="light"></div>'+
                        '<div class="tank_filter class medium" title="medium"></div>'+
                        '<div class="tank_filter class heavy" title="heavy"></div>'+
                        '<div class="tank_filter class td" title="td"></div>'+
                        '<div class="tank_filter class spg" title="spg"></div>'+
                    '</div>'+
                    '<select class="tank_select" id="tank_select_'+column_count+'">'+
                        '<option value="0">--SELECT--</option>'+
                    '</select>'+
                    '<div class="module_selects">'+
                        '<select class="gun_select">'+
                            '<option value="0">--gun--</option>'+
                        '</select>'+
                        '<br>'+
                        '<select class="turret_select">'+
                            '<option value="0">--turret--</option>'+
                        '</select>'+
                        '<br>'+
                        '<select class="suspension_select">'+
                            '<option value="0">--suspension--</option>'+
                        '</select>'+
                        '<br>'+
                        '<select class="engine_select">'+
                            '<option value="0">--engine--</option>'+
                        '</select>'+
                        '<br>'+
                        '<select class="radio_select">'+
                            '<option value="0">--radio--</option>'+
                        '</select>'+
                    '</div>'+
                '</td>';
    return filters;
}

submitFilters = function(target)
{
    var tank_select = $(target).find('.tank_select');
    tank_select.html('');
    var nation_filters = [];
    $(target).find('.nation.active').each(function(){
        nation_filters.push($(this).attr('title'));
    });
    var class_filters = [];
    $(target).find('.class.active').each(function(){
        class_filters.push($(this).attr('title'));
    });
    var tier_filters = [];
    $(target).find('.tier.active').each(function(){
        tier_filters.push(convertRomanNum($(this).text()));
    });
    var data = {
        'action' : 'getTankOptions',
        'nation' : nation_filters,
        'class'  : class_filters,
        'tier'   : tier_filters
    }
    showLoading();
    $.post('AjaxHandler.php', data, function(options){
        console.log(options);
        options = JSON.parse(options);

        tank_select.append($('<option></option>')
            .attr('value', '0')
            .text('--SELECT--'));
        $.each(options, function(index, item){
            tank_select.append($('<option></option>')
                .attr('value', item['id'])
                .text(item['name']));
        });
        hideLoading();
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

setModuleOptions = function(type, modules, isElite)
{
    isElite = 0;
    var container = $('#row_tank').children()[tank_num+1];
    var module_select = $($(container).find('.'+type+'_select'));
    module_select.html('');
    module_select.append($('<option></option>')
        .attr('value', '0')
        .text('--'+type+'--'));
    $.each(modules, function(i, module){
        if(type === 'gun' && module.elite != isElite) return;
        module_select.append($('<option></option')
            .attr('value', i)
            .text(module.name));
    });
}

getEliteVersion = function(gun)
{
    var current_tank = current_tanks[tank_num];
    var versions = [];
    $.each(current_tank.guns, function(){
        if(this.name === gun.name){
            versions.push(this);
        }
    });
    return versions.pop();
}

getBestGun = function(guns)
{
    var stock_guns = $.map(guns, function(gun, index){
        return parseInt(gun.elite) ? null : gun;
    });
    return stock_guns.pop();
}

function convertRomanNum(num)
{
    num = num+'';
    switch (num){
        case 'I':
            return 1;
        case 'II':
            return 2;
        case 'III':
            return 3;
        case 'IV':
            return 4;
        case 'V':
            return 5;
        case 'VI':
            return 6;
        case 'VII':
            return 7;
        case 'VIII':
            return 8;
        case 'IX':
            return 9;
        case 'X':
            return 10;
        default:
            return 'RomanNumeral Error';
    }
}

showLoading = function()
{
    $('body').css('cursor', 'progress');
    $('#modal').show();
}

hideLoading = function()
{
    $('body').css('cursor', 'auto');
    $('#modal').hide();
}

$(function(){
    appendColumn();
    appendColumn();

    $('body').on('change', '.tank_select', function(){
        tank_num = parseInt($(this).attr('id').split('_')[2]);
        var id = $(this).val();
        showLoading();
        $.post('AjaxHandler.php', {action : 'loadTank', tank_id : id}, function(data, status, jq){
           var tank_data = JSON.parse(data);
           current_tanks[tank_num] = tank_data;
           loadTankStats(tank_data);
           setModuleOptions('gun', tank_data['guns']);
           setModuleOptions('turret', tank_data['turrets']);
           setModuleOptions('suspension', tank_data['suspensions']);
           setModuleOptions('engine', tank_data['engines']);
           setModuleOptions('radio', tank_data['radios']);
           compareAll();
           hideLoading();
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
        appendColumn();
        if(column_count === 6) $(this).hide();
        $('#remove_tank').show();
    })

    $('#remove_tank').on('click', function(){
        removeColumn();
        if(column_count === 1) $(this).hide();
        $('#add_tank').show();
    })

    $('body').on('click', '.tank_filter', function(){
        $(this).toggleClass('active');
        var column = $(this).closest('td');
        submitFilters(column);
    })

    $('#toggle_modules').on('click', function(){
        var label = $('.module_selects').is(':hidden') ? 'Hide' : 'Show';
        $(this).val(label+' Modules');
        $('.module_selects').toggle();
    })

    $('body').on('change', '.gun_select', function(){
        tank_num = $(this).closest('td').index()-1;
        loadGunStats(current_tanks[tank_num].guns[$(this).val()]);
        compareAll();
    })

    $('body').on('change', '.turret_select', function(){
        tank_num = $(this).closest('td').index()-1;
        var turret_index = parseInt($(this).val());
        loadTurretStats(current_tanks[tank_num].turrets[turret_index], turret_index);
        compareAll();
    })

    $('body').on('change', '.suspension_select', function(){
        tank_num = $(this).closest('td').index()-1;
        loadSuspensionStats(current_tanks[tank_num].suspensions[$(this).val()]);
        compareAll();
    })

    $('body').on('change', '.engine_select', function(){
        tank_num = $(this).closest('td').index()-1;
        loadEngineStats(current_tanks[tank_num].engines[$(this).val()]);
        compareAll();
    })

    $('body').on('change', '.radio_select', function(){
        tank_num = $(this).closest('td').index()-1;
        loadRadioStats(current_tanks[tank_num].radios[$(this).val()]);
        compareAll();
    })
});