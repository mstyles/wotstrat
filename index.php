<html>
    <head>
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
        <script src='wotstrat.js'></script>
        <style>
            #loading{
                display: none;
            }
            #comparison_table{
                border-collapse: collapse;
            }
            #comparison_table{
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h2>Tank vs Tank</h2>
        <p id='loading'>Processing...</p>
        <input type="text" class='tank_id' />
        <br>
        <input type="radio" class="tank_filter" name="filter_nation" value='usa'>USA
        <input type="radio" class="tank_filter" name="filter_nation" value='germany'>Germany
        <input type="radio" class="tank_filter" name="filter_nation" value='russia'>Russia
        <br>
        <input type="radio" class="tank_filter" name="filter_tier" value='5'>5
        <input type="radio" class="tank_filter" name="filter_tier" value='6'>6
        <input type="radio" class="tank_filter" name="filter_tier" value='7'>7
        <br>
        <input type="radio" class="tank_filter" name="filter_class" value='light'>Light
        <input type="radio" class="tank_filter" name="filter_class" value='td'>TD
        <input type="radio" class="tank_filter" name="filter_class" value='heavy'>Heavy
        <select id="tank_select"></select>
        <div>
            <input type='button' class='load_tank' value='Load Tank' />
            <input type='button' class='load_tank' value='Load Tank' />
        </div>
        <div id='tank_1' class='tank_box'></div>
        <br>
        <table id='comparison_table' border="1">
            <tbody>
            <tr id="row_name">
                <th>Name</th>
            </tr>
            <tr id="row_nation">
                <th>Nation</th>
            </tr>
            <tr id="row_tier">
                <th>Tier</th>
            </tr>
            <tr id="row_class">
                <th>Class</th>
            </tr>
            <tr id="row_health">
                <th>Health</th>
            </tr>
            <tr id="row_speed_limit">
                <th>Max Speed</th>
            </tr>
            <tr id="row_weight">
                <th>Weight</th>
            </tr>
            <tr id="row_horsepower">
                <th>Horsepower</th>
            </tr>
            <tr id="row_hp_per_ton">
                <th>HP per Ton</th>
            </tr>
            <tr id="row_traverse_speed">
                <th>Traverse Speed</th>
            </tr>
            <tr id="row_hull_armor">
                <th>Hull Armor</th>
            </tr>
            <tr id="row_turret_armor">
                <th>Turret Armor</th>
            </tr>
            <tr id="row_turret_traverse">
                <th>Turret Traverse</th>
            </tr>
            <tr id="row_view_range">
                <th>View Range</th>
            </tr>
            <tr id="row_signal_range">
                <th>Signal Range</th>
            </tr>
            <tr id="row_gun">
                <th>Gun</th>
            </tr>
            <tr id="row_rate_of_fire">
                <th>Rate of Fire</th>
            </tr>
            <tr id="row_pen_ap">
                <th>AP Pen</th>
            </tr>
            <tr id="row_dmg_ap">
                <th>AP Damage</th>
            </tr>
            <tr id="row_pen_he">
                <th>HE Pen</th>
            </tr>
            <tr id="row_dmg_he">
                <th>HE Damage</th>
            </tr>
            <tr id="row_accuracy">
                <th>Accuracy</th>
            </tr>
            <tr id="row_aim_time">
                <th>Aim Time</th>
            </tr>
            <tr id="row_ap_dps">
                <th>AP DPS</th>
            </tr>
            <tr id="row_he_dps">
                <th>HE DPS</th>
            </tr>
            </tbody>
        </table>
    </body>
</html>