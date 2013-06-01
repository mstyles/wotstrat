<?php
require_once('header.php');
?>
<script type="text/javascript">
    page = 'tankvtank';
</script>
<!--Begin Content-->
<div id="content-wrapper-inner">
<div id="main-content">
<h1 class="page-title">Tank Vs. Tank</h1>
<span>Spot something wrong? <span id="report_link">Report it!</span></span>
<table id='comparison_table' border="1">
    <tbody>
    <tr id="row_tank">
        <td>
            <input type='button' id='add_tank' class='btn_controls' value='Add Tank' />
            <br>
            <input type='button' id='remove_tank' class='btn_controls' value='Remove Tank' />
            <br>
            <input type='button' id='toggle_modules' class='btn_controls' value='Show Modules' />
        </td>
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

</div>
<!--End Main Content-->
</div>
<!--End Content Wrapper-->
<div id="feedback_pop">
    <table border="0">
        <tr>
            <td>
                <span>Email:</span>
            </td>
            <td colspan="3">
                <input type="text" id="email_address"/><span style="color:gold">*optional</span>
            </td>
        </tr>
        <tr>
            <td>
                <span>Tank:</span>
            </td>
            <td>
                <input type="text" id="bugged_tank"/>
            </td>
            <td>
                <span>Attribute:</span>
            </td>
            <td>
                <input type="text" id="bugged_attribute"/>
            </td>
        </tr>
    </table>
    <input type="hidden" id="feedback_type" value="bug"/>
    <div align="center">
        <input type="button" id="cancel_btn" value="cancel" />
        <input type="button" id="submit_btn" value="submit" />
    </div>
</div>
<?php require_once('footer.php') ?>
