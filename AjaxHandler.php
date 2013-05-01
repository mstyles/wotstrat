<?php
require_once 'TankManager.php';

$req = $_REQUEST;

switch($req['action']){
    case 'loadTank':
        loadTank($req['tank_id']);
        break;
    case 'getTankOptions':
        getTankOptions($req['tank_id']);
        break;
    default:
        echo 'Unrecognized action';
}

?>
