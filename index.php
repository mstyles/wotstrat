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
                text-align: center;
            }
            #comparison_table td{
                min-width: 150px;
            }
        </style>
    </head>
    <body>
        <h2>Tank vs Tank</h2>
        <p id='loading'>Processing...</p>
        <span>Enter tank id manually: </span><input type="text" class='tank_id' />
        <br>
        <span>Or use the filters: </span>
        <br>
        <input type="radio" class="tank_filter" name="filter_nation" value='usa'>USA
        <input type="radio" class="tank_filter" name="filter_nation" value='germany'>Germany
        <input type="radio" class="tank_filter" name="filter_nation" value='ussr'>Russia
        <input type="radio" class="tank_filter" name="filter_nation" value='france'>France
        <input type="radio" class="tank_filter" name="filter_nation" value='china'>China
        <input type="radio" class="tank_filter" name="filter_nation" value='uk'>UK
        <input type="button" id="filter_nation_clear" value="Clear" />
        <br>
        <input type="radio" class="tank_filter" name="filter_tier" value='1'>1
        <input type="radio" class="tank_filter" name="filter_tier" value='2'>2
        <input type="radio" class="tank_filter" name="filter_tier" value='3'>3
        <input type="radio" class="tank_filter" name="filter_tier" value='4'>4
        <input type="radio" class="tank_filter" name="filter_tier" value='5'>5
        <input type="radio" class="tank_filter" name="filter_tier" value='6'>6
        <input type="radio" class="tank_filter" name="filter_tier" value='7'>7
        <input type="radio" class="tank_filter" name="filter_tier" value='8'>8
        <input type="radio" class="tank_filter" name="filter_tier" value='9'>9
        <input type="radio" class="tank_filter" name="filter_tier" value='10'>10
        <input type="button" id="filter_tier_clear" value="Clear" />
        <br>
        <input type="radio" class="tank_filter" name="filter_class" value='light'>Light
        <input type="radio" class="tank_filter" name="filter_class" value='medium'>Medium
        <input type="radio" class="tank_filter" name="filter_class" value='heavy'>Heavy
        <input type="radio" class="tank_filter" name="filter_class" value='td'>TD
        <input type="radio" class="tank_filter" name="filter_class" value='spg'>SPG
        <input type="button" id="filter_class_clear" value="Clear" />
        <br>
        <select id="tank_select"></select>
        <div id='button_container'>
            <input type='button' class='load_tank' value='Load Tank 1' />
            <input type='button' class='load_tank' value='Load Tank 2' />
            
        </div>
        <input type='button' id='add_tank' value='Add Tank' />
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