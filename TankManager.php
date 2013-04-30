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
        WHERE t.id = $tank_id
    ";
    
    $result = query($sql);
    $tank = new Tank($result);
    $tank->loadModules();
    echo json_encode($tank->getJsonData());
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