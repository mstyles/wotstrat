<?php
class Suspension extends Module
{
    protected $load_limit;
    
    protected $traverse_speed;
    
    function __construct($properties)
    {
        foreach($properties as $key => $value){
            if(property_exists(Suspension, $key) || property_exists(Module, $key)){
                $this->$key = $value;   
            }
        }
        parent::__construct($properties);
    }
        
    function toSql($tank_id)
    {
        $sql = "
            INSERT IGNORE INTO `suspensions`
                (tank_id, tier, name, price, xp_cost, weight,
                load_limit, traverse_speed)
            VALUES
                ({$tank_id}, {$this->tier}, '{$this->name}', {$this->price}, {$this->xp_cost}, {$this->weight},
                {$this->load_limit}, {$this->traverse_speed})
            ON DUPLICATE KEY UPDATE
                tier = {$this->tier},
                price = {$this->price},
                xp_cost = {$this->xp_cost},
                weight = {$this->weight},
                load_limit = {$this->load_limit},
                traverse_speed = {$this->traverse_speed}
        ";
        return $sql;
    }
    
    function getLoadLimit()
    {
        return $this->load_limit;
    }
    
    function getTraverseSpeed()
    {
        return $this->traverse_speed;
    }
}
?>