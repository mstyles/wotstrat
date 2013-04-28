<?php
class Radio extends Module
{
    protected $range;
    
    function __construct($properties)
    {
        foreach($properties as $key => $value){
            if(property_exists(Radio, $key) || property_exists(Module, $key)){
                $this->$key = $value;   
            }
        }
        parent::__construct($properties);
    }
        
    function toSql($tank_id)
    {
        $sql = "
            INSERT IGNORE INTO `radios`
                (tank_id, tier, name, price, xp_cost, weight,
                range)
            VALUES
                ({$tank_id}, {$this->tier}, '{$this->name}', {$this->price}, {$this->xp_cost}, {$this->weight},
                {$this->range})
            ON DUPLICATE KEY UPDATE
                tier = {$this->tier},
                price = {$this->price},
                xp_cost = {$this->xp_cost},
                weight = {$this->weight},
                range = {$this->range}
        ";
        return $sql;
    }
    
    function getRange()
    {
        return $this->range;
    }
    
    function setRange($range)
    {
        $this->range = $range;
    }
}
?>