<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/comparator.css">
    </head>
    <body>
        <table id='comparison_table' border="1">
            <tbody><tr>
                <td>
                    <h2>Tank vs Tank</h2>
                    <input type='button' id='add_tank' value='Add Tank' />
                </td>
                <!--<td>
                    <div class="filter_wrap">
                        <div class="tank_filter nation usa" title="usa"></div>
                        <div class="tank_filter nation germany" title="germany"></div>
                        <div class="tank_filter nation ussr" title="ussr"></div>
                        <div class="tank_filter nation france" title="france"></div>
                        <div class="tank_filter nation china" title="china"></div>
                        <div class="tank_filter nation uk" title="uk"></div>

                        <div class="tank_filter tier">I</div>
                        <div class="tank_filter tier">II</div>
                        <div class="tank_filter tier">III</div>
                        <div class="tank_filter tier">IV</div>
                        <div class="tank_filter tier">V</div>
                        <div class="tank_filter tier">VI</div>
                        <div class="tank_filter tier">VII</div>
                        <div class="tank_filter tier">VIII</div>
                        <div class="tank_filter tier">IX</div>
                        <div class="tank_filter tier">X</div>

                        <div class="tank_filter class light" title="light"></div>
                        <div class="tank_filter class medium" title="medium"></div>
                        <div class="tank_filter class heavy" title="heavy"></div>
                        <div class="tank_filter class td" title="td"></div>
                        <div class="tank_filter class spg" title="spg"></div>
                    </div>
                    <select class="tank_select" id="tank_0">
                        <option value="0">--SELECT--</option>
                    </select>
                </td>-->
                </tr>
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
                <th>Speed Limit</th>
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
                <th>Hull Traverse</th>
            </tr>
            <tr id="row_pivot">
                <th>Pivot</th>
            </tr>
            <tr id="row_hull_armor">
                <th>Hull Armor</th>
            </tr>
            <tr id="row_turret_armor" class="turreted_field">
                <th>Turret Armor</th>
            </tr>
            <tr id="row_turret_traverse" class="turreted_field">
                <th>Turret Traverse</th>
            </tr>
            <tr id="row_gun_elevation">
                <th>Gun Elevation</th>
            </tr>
            <tr id="row_view_range">
                <th>View Range</th>
            </tr>
            <tr id="row_signal_range">
                <th>Signal Range</th>
            </tr>
            <tr id="row_gun_arc" class="non_turreted_field">
                <th>Gun Arc</th>
            </tr>
            <tr id="row_gun_traverse" class="non_turreted_field">
                <th>Gun Traverse</th>
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
            <tr id="row_pen_gold">
                <th>Gold Pen</th>
            </tr>
            <tr id="row_dmg_gold">
                <th>Gold Damage</th>
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
            <tr id="row_gold_dps">
                <th>Gold DPS</th>
            </tr>
            <tr id="row_ammo">
                <th>Max Ammo</th>
            </tr>
            </tbody>
        </table>
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
        <script src='wotstrat.js'></script>
    </body>
</html>