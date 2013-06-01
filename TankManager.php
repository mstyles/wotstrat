<?php
require_once 'helpers.php';

connectDb();

function loadTank($tank_id)
{
    $sql = "
        SELECT
            t.*
        FROM tanks t
        WHERE t.id = $tank_id
    ";

    $result = queryOneRow($sql);
    $tank = new Tank($result);
    $tmp_weight = $tank->getChassisWeight();
    $tank->loadModules();
    $tank->setChassisWeight($tmp_weight);
    echo json_encode($tank->getJsonData());
}

function getTankOptions()
{
    $where_clauses = array();
    if(!empty($_REQUEST['nation'])){
        $nation_filters = implode("','", $_REQUEST['nation']);
        $where_clauses[] = "nation IN ('$nation_filters')";
    }
    if(!empty($_REQUEST['class'])){
        $class_filters = implode("','", $_REQUEST['class']);
        $where_clauses[] = "class IN ('$class_filters')";
    }
    if(!empty($_REQUEST['tier'])){
        $tier_filters = implode(',', $_REQUEST['tier']);
        $where_clauses[] = "tier IN ($tier_filters)";
    }
    $where = implode(' AND ', $where_clauses);
    $where = 'WHERE '.$where;
    $sql = "SELECT id, name FROM tanks ".$where;
    $results = queryAll($sql);
    echo json_encode($results);
}
?>