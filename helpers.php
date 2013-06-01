<?php
function connectDb()
{
    if(php_uname('n') == 'is2.byuh.edu'){
        mysql_connect("localhost", "wot_admin", "poop");
        mysql_select_db("wot_data");
    } else{
        mysql_connect("us-cdbr-azure-northcentral-a.cleardb.com", "bf440d47033236", "4c96486f");
        mysql_select_db("wotdb");
    }
}

function queryOneRow($sql)
{
    $result = mysql_query($sql);
    if (!$result){
        echo mysql_error();
        echo $sql;
        exit();
    } else {
        return mysql_fetch_assoc($result);
    }
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

function queryInsert($sql)
{
    $results = mysql_query($sql);
    if (!$results){
        echo mysql_error();
        echo $sql;
        exit();
    } else {
        return mysql_insert_id();
    }
}

function __autoload($class_name)
{
    include $class_name . '.php';
}
?>
