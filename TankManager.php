<?php
mysql_connect("localhost", "wot_admin", "poop");
mysql_select_db("wot_data");

$req = $_REQUEST;

switch($req['action']){
    case 'loadTank':
        loadTank($req['tank_id']);
        break;
    default:
        echo 'Unrecognized action';
}

function loadTank($tank_id)
{
    $sql = "
        SELECT
            t.*
        FROM tanks t
        WHERE t.id = 20
    ";
    
    $result = query($sql);
    $tank = new Tank($result);
    //utf8_encode_deep($tank);
    var_dump($tank);
    echo '<br> JSON: <br>';
    echo $tank->toJson();
    echo '<br><br><br>';
    $tank->loadModules();
    $tank_guns = $tank->getGuns();
    var_dump($tank_guns[0]);
    echo '<br><br><br>';
    //echo $tank_guns[0]->toJson();
    echo json_encode($tank_guns[0]);
}

function utf8_encode_deep(&$input) {
    if (is_string($input)) {
        $input = utf8_encode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
    }
}

function query($sql)
{
    //return null;
    $result = mysql_query($sql);
    if (!$result){
        echo mysql_error();
        echo $sql;
        exit();
    } else {
        return mysql_fetch_assoc($result);
    }
}

function __autoload($class_name)
{
    include $class_name . '.php';
}

?>