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
    appendCell('row_horsepower', tank_data['todo']);
    appendCell('row_hp_per_ton', tank_data['todo']);
    appendCell('row_traverse_speed', tank_data['speed_limit']);
    var hull_armor = tank_data['armor_front']+'/'+tank_data['armor_side']+'/'+tank_data['armor_rear'];
    appendCell('row_hull_armor', hull_armor);
    appendCell('row_turret_armor', hull_armor);
    appendCell('row_turret_traverse', tank_data['hp_elite']);
    appendCell('row_view_range', tank_data['view_range']);
    appendCell('row_signal_range', tank_data['todo']);
    appendCell('row_gun', tank_data['todo']);
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
        var id = Math.floor(Math.random()*101);
        $.post('TankManager.php', {action : 'loadTank', tank_id : id}, function(data, status, jq){
           $('#tank_1').html(data);
           var tank_data = JSON.parse(data);
           loadTankStats(tank_data);
        });
    })
});