var timer = 0;

incrementTimer = function()
{
    $('#counter').text(timer);
    timer++;
}

loadTankStats = function(tank_data)
{
    appendCell('row_name', tank_data['name']);
    appendCell('row_nation', tank_data['nation']);
    appendCell('row_tier', tank_data['tier']);
    appendCell('row_class', tank_data['class']);
    appendCell('row_health', tank_data['hp_elite']);
    appendCell('row_speed_limit', tank_data['speed_limit']);
    appendCell('row_weight', tank_data['todo']);
    var hull_armor = tank_data['armor_front']+'/'+tank_data['armor_side']+'/'+tank_data['armor_rear'];
    appendCell('row_hull_armor', hull_armor);
    var gun = tank_data['guns'].pop();
    var turret = tank_data['turrets'].slice(-1).pop();
    var engine = tank_data['engines'].pop();
    var suspension = tank_data['suspensions'].pop();
    var radio = tank_data['radios'].pop();
    loadGunStats(gun);
    if(tank_data['turrets'].length > 0){
        loadTurretStats(turret);
    } else {
        appendCell('row_view_range', tank_data['view_range']);
        loadBlankTurretStats();
    }
    loadEngineStats(engine);
    loadSuspensionStats(suspension);
    loadRadioStats(radio);
}

loadGunStats = function(gun)
{
    appendCell('row_gun', gun['name']);
    var rof = gun['rate_of_fire'];
    appendCell('row_rate_of_fire', rof);
    appendCell('row_pen_ap', gun['pen_ap']);
    var dmg_ap = gun['damage_ap'];
    appendCell('row_dmg_ap', dmg_ap);
    appendCell('row_pen_he', gun['pen_he']);
    var dmg_he = gun['damage_he'];
    appendCell('row_dmg_he', gun['damage_he']);
    appendCell('row_accuracy', gun['accuracy']);
    appendCell('row_aim_time', gun['aim_time']);
    var ap_dps = (rof * dmg_ap / 60).toFixed(2);
    var he_dps = (rof * dmg_he / 60).toFixed(2);
    appendCell('row_ap_dps', ap_dps);
    appendCell('row_he_dps', he_dps);
}

loadTurretStats = function(turret)
{
    var turret_armor = turret['armor_front']+
        '/'+turret['armor_side']+
        '/'+turret['armor_rear'];
    appendCell('row_turret_armor', turret_armor);
    appendCell('row_turret_traverse', turret['traverse_speed']);
    appendCell('row_view_range', turret['view_range']);
}

loadBlankTurretStats = function()
{
    appendCell('row_turret_armor', '--');
    appendCell('row_turret_traverse', '--');
}

loadEngineStats = function(engine)
{
    appendCell('row_horsepower', engine['power']);
    appendCell('row_hp_per_ton', engine['power']);
}

loadSuspensionStats = function(suspension)
{
    appendCell('row_traverse_speed', suspension['traverse_speed']);
}

loadRadioStats = function(radio)
{
    appendCell('row_signal_range', radio['range']);
}

appendCell = function(row_id, value)
{
    $('#'+row_id).append('<td>'+value+'</td>');
}

$(function(){
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
    
    $('#load_tank').on('click', function(){
        var id;
        if($('#tank_id').val()){
            id = $('#tank_id').val();
        } else {
            id = Math.floor(Math.random()*101);
        }
        $.post('TankManager.php', {action : 'loadTank', tank_id : id}, function(data, status, jq){
           var tank_data = JSON.parse(data);
           loadTankStats(tank_data);
        });
    })
});