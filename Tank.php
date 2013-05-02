<?php
class Tank
{
    private $id;
    
    private $class;
    
    private $nation;
    
    private $name;
    
    private $tier;
    
    private $price;
    
    private $hp_stock;
    
    private $hp_elite;
    
    private $crew;
    
    private $speed_limit;
    
    private $pivot;
    
    private $armor_front;
    
    private $armor_side;
    
    private $armor_rear;
    
    private $gun_traverse_speed = 0;
    
    private $gun_arc_left;
    
    private $gun_arc_right = 0;
    
    private $view_range;
    
    private $chassis_weight = 0;
    
    private $stock_weight;
    
    private $premium = 0;
    
    private $gun_names = array();
    
    private $guns = array();
    
    private $turrets = array();
    
    private $suspensions = array();
    
    private $engines = array();
    
    private $radios = array();
    
    function __construct($properties)
    {
        foreach($properties as $key => $value){
            $this->$key = $value;
        }
        if(!$this->chassis_weight){
            $this->chassis_weight = $this->stock_weight * 1000;
        }
    }
    
    function __toString()
    {
        return $this->name.' '.$this->tier;
    }
    
    function generateSqlAndInsert()
    {
        /* Write in such a way that it can be run multiple times,
         * each time merely overwriting/updating existing values
         */
        
        //echo '<br><br>Final weight: '.$this->chassis_weight;
        
        $sql = $this->toSql();
        
        $this->id = $this->queryInsert($sql);
        
        $gun_names = array();
        foreach ($this->guns as $gun){
            
            $sql = $gun->toSql($this->id);
            $this->queryInsert($sql);
        }
        
        foreach ($this->turrets as $turret){
            $sql = $turret->toSql($this->id);
            $this->queryInsert($sql);
        }
        
        foreach ($this->suspensions as $suspension){
            $sql = $suspension->toSql($this->id);
            $this->queryInsert($sql);
        }
        
        foreach ($this->engines as $engine){
            $sql = $engine->toSql($this->id);
            $this->queryInsert($sql);
        }
        
        foreach ($this->radios as $radio){
            $sql = $radio->toSql($this->id);
            $this->queryInsert($sql);
        }
        
    }
    
    function toSql()
    {
        $sql = "
            INSERT INTO tanks
                (class, nation, name, tier, price,
                hp_stock, hp_elite, crew, speed_limit, pivot,
                armor_front, armor_side, armor_rear, gun_traverse_speed,
                gun_arc_left, gun_arc_right, view_range, chassis_weight)
            VALUES
                ('{$this->class}', '{$this->nation}', '{$this->name}', {$this->tier}, {$this->price},
                {$this->hp_stock}, {$this->hp_elite}, '{$this->crew}', {$this->speed_limit}, {$this->pivot},
                {$this->armor_front}, {$this->armor_side}, {$this->armor_rear}, {$this->gun_traverse_speed},
                {$this->gun_arc_left}, {$this->gun_arc_right}, {$this->view_range}, {$this->chassis_weight})
            ON DUPLICATE KEY UPDATE
                tier = {$this->tier},
                price = {$this->price},
                hp_stock = {$this->hp_stock},
                hp_elite = {$this->hp_elite},
                crew = '{$this->crew}',
                speed_limit = {$this->speed_limit},
                pivot = {$this->pivot},
                armor_front = {$this->armor_front},
                armor_side = {$this->armor_side},
                armor_rear = {$this->armor_rear},
                gun_traverse_speed = {$this->gun_traverse_speed},
                gun_arc_left = {$this->gun_arc_left},
                gun_arc_right = {$this->gun_arc_right},
                view_range = {$this->view_range},
                chassis_weight = {$this->chassis_weight},
                id = LAST_INSERT_ID(id)
        ";
        return $sql;
    }

    function getJsonData(){
        unset($this->gun_names);
        $vars = get_object_vars($this);
        foreach($vars as &$var){
            if(is_array($var)){
                foreach ($var as &$array_var){
                    if(is_object($array_var) && method_exists($array_var,'getJsonData')){
                        $array_var = $array_var->getJsonData();
                    }
                }
            }
        }
        return $vars;
     }
    
    function toJson()
    {
        return json_encode($this);
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
            while($row = mysql_fetch_array($result)){
                $results[] = $row;   
            }
            return $results;
        }
    }
    
    function addGun($gun)
    {
        if(in_array($gun->getName(), $this->gun_names)){
            $gun->setElite(1);
        } else {
            $this->gun_names[] = $gun->getName();
        }
        if($gun->getXpCost() == 0 && $gun->getElite() == 0){
            //echo 'subtracting gun '.$gun->getWeight().' from '.$this->chassis_weight.'<br>';
            $this->chassis_weight -= $gun->getWeight();
        }
        $this->guns[] = $gun;
    }
    
    function addTurret($turret)
    {
        if($turret->getXpCost() == 0){
            //echo 'subtracting turret '.$turret->getWeight().' from '.$this->chassis_weight.'<br>';
            $this->chassis_weight -= $turret->getWeight();
        }
        $this->turrets[] = $turret;
    }
    
    function addSuspension($suspension)
    {
        if($suspension->getXpCost() == 0){
            //echo 'subtracting suspen '.$suspension->getWeight().' from '.$this->chassis_weight.'<br>';
            $this->chassis_weight -= $suspension->getWeight();
        }
        $this->suspensions[] = $suspension;
    }
    
    function addEngine($engine)
    {
        if($engine->getXpCost() == 0){
            //echo 'subtracting engine '.$engine->getWeight().' from '.$this->chassis_weight.'<br>';
            $this->chassis_weight -= $engine->getWeight();
        }
        $this->engines[] = $engine;
    }
    
    function addRadio($radio)
    {
        if($radio->getXpCost() == 0){
            //echo 'subtracting radio '.$radio->getWeight().' from '.$this->chassis_weight.'<br>';
            $this->chassis_weight -= $radio->getWeight();
        }
        $this->radios[] = $radio;
    }
    
    function getGuns()
    {
        
        return $this->guns;   
    }
    
    function getTurrets()
    {
        return $this->turrets;
    }
    
    function getSuspensions()
    {
        return $this->suspensions;
    }
    
    function getEngines()
    {
        return $this->engines;
    }
    
    function getRadios()
    {
        return $this->radios;
    }
    
    function getClass()
    {
        return $this->class;
    }
    
    function getNation()
    {
        return $this->nation;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function getTier()
    {
        return $this->tier;
    }
    
    function getPrice()
    {
        return $this->price;
    }
    
    function getHpStock()
    {
        return $this->hp_stock;
    }
    
    function getHpElite()
    {
        return $this->hp_elite;
    }
    
    function getCrew()
    {
        return $this->crew;
    }
    
    function getSpeedLimit()
    {
        return $this->speed_limit;
    }
    
    function getPivot()
    {
        return $this->pivot;
    }
    
    function getArmorFront()
    {
        return $this->armor_front;
    }
    
    function getArmorSide()
    {
        return $this->armor_side;
    }
    
    function getArmorRear()
    {
        return $this->armor_rear;
    }
    
    function getGunTraverseSpeed()
    {
        return $this->gun_traverse_speed;
    }
    
    function getGunArcLeft()
    {
        return $this->gun_arc_left;
    }
    
    function getGunArcRight()
    {
        return $this->gun_arc_right;
    }
    
    function getViewRange()
    {
        return $this->view_range;
    }
    
    function getChassisWeight()
    {
        return $this->chassis_weight;
    }

    function setChassisWeight($chassis_weight)
    {
        $this->chassis_weight = $chassis_weight;
    }
    
    function loadModules()
    {
        $this->loadGuns();
        $this->loadEngines();
        $this->loadTurrets();
        $this->loadSuspensions();
        $this->loadRadios();
    }
    
    function loadGuns()
    {
        $sql = "
            SELECT *
            FROM guns
            WHERE tank_id = {$this->id}
            ORDER BY price
        ";
        
        $guns = $this->queryAll($sql);
        
        foreach($guns as $gun){
            $gun = new Gun($gun);
            $this->addGun($gun);
        }
    }
    
    function loadEngines()
    {
        $sql = "
            SELECT *
            FROM engines
            WHERE tank_id = {$this->id}
            ORDER BY price
        ";
        
        $engines = $this->queryAll($sql);
        
        foreach($engines as $engine){
            $engine = new Engine($engine);
            $this->addEngine($engine);
        }
    }
    
    function loadTurrets()
    {
        $sql = "
            SELECT *
            FROM turrets
            WHERE tank_id = {$this->id}
            ORDER BY price
        ";
        
        $turrets = $this->queryAll($sql);
        
        foreach($turrets as $turret){
            $turret = new Turret($turret);
            $this->addTurret($turret);
        }
    }
    
    function loadSuspensions()
    {
        $sql = "
            SELECT *
            FROM suspensions
            WHERE tank_id = {$this->id}
            ORDER BY price
        ";
        
        $suspensions = $this->queryAll($sql);
        
        foreach($suspensions as $suspension){
            $suspension = new Suspension($suspension);
            $this->addSuspension($suspension);
        }
    }
    
    function loadRadios()
    {
        $sql = "
            SELECT *
            FROM radios
            WHERE tank_id = {$this->id}
            ORDER BY price
        ";
        
        $radios = $this->queryAll($sql);
        
        foreach($radios as $radio){
            $radio = new Radio($radio);
            $this->addRadio($radio);
        }
    }
    
    public function __destruct()
    {
        foreach ($this->guns as &$gun){
            $gun = null;
        }
        
        foreach ($this->turrets as &$turret){
            $turret = null;
        }
        
        foreach ($this->suspensions as &$suspension){
            $suspension = null;
        }
        
        foreach ($this->engines as &$engine){
            $engine = null;
        }
        
        foreach ($this->radios as &$radio){
            $radio = null;
        }
    }
}
?>