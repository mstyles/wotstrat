<?php
mysql_connect("localhost", "wot_admin", "poop");
mysql_select_db("wot_data");

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

function getTankOptions()
{
    $where_clauses = array();
    if(isset($_REQUEST['nation'])){
        $where_clauses[] = "nation = '{$_REQUEST['nation']}'";
    }
    if(isset($_REQUEST['class'])){
        $where_clauses[] = "class = '{$_REQUEST['class']}'";
    }
    if(isset($_REQUEST['tier'])){
        $where_clauses[] = "tier = '{$_REQUEST['tier']}'";
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