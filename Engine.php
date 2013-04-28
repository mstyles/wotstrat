<?php
class Engine extends Module
{
    protected $power;
    
    protected $fire_chance;
    
    protected $type;

    function __construct($properties)
    {
        foreach($properties as $key => $value){
            if(property_exists(Engine, $key) || property_exists(Module, $key)){
                $this->$key = $value;   
            }
        }
        parent::__construct($properties);
    }
        
    function toSql($tank_id)
    {
        $sql = "
            INSERT IGNORE INTO `engines`
                (tank_id, tier, name, price, xp_cost, weight,
                power, fire_chance, type)
            VALUES
                ({$tank_id}, {$this->tier}, '{$this->name}', {$this->price}, {$this->xp_cost}, {$this->weight},
                {$this->power}, {$this->fire_chance}, '{$this->type}')
            ON DUPLICATE KEY UPDATE
                tier = {$this->tier},
                price = {$this->price},
                xp_cost = {$this->xp_cost},
                weight = {$this->weight},
                power = {$this->power},
                fire_chance = {$this->fire_chance},
                type = '{$this->type}'
        ";
        return $sql;
    }
    
    function getPower()
    {
        return $this->power;
    }
    
    function getFireChance()
    {
        return $this->fire_chance;
    }
    
    function getType()
    {
        return $this->type;
    }
}
?>