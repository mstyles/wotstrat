<?php
if(php_uname('n') == 'is2.byuh.edu'){
    mysql_connect("localhost", "wot_admin", "poop");
    mysql_select_db("wot_data");
} else{
    mysql_connect("us-cdbr-azure-northcentral-a.cleardb.com", "bf440d47033236", "4c96486f");
    mysql_select_db("wotdb");
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

function queryAll($sql)
{
    $result = mysql_query($sql);
    if (!$result){
        echo mysql_error();
        echo $sql;
        exit();
    } else {
        $results = array();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $results[] = $row;
        }
        return $results;
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