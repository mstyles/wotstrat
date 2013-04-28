<?php
class Turret extends Module
{
    protected $traverse_speed;
    
    protected $traverse_arc;
    
    protected $view_range;
    
    protected $armor_front;
    
    protected $armor_side;
    
    protected $armor_rear;
    
    function __construct($properties)
    {
        foreach($properties as $key => $value){
            if(property_exists(Turret, $key) || property_exists(Module, $key)){
                $this->$key = $value;   
            }
        }
        parent::__construct($properties);
    }
        
    function toSql($tank_id)
    {
        $sql = "
            INSERT INTO `turrets`
                (tank_id, tier, name, price, xp_cost, weight,
                traverse_speed, traverse_arc, view_range, armor_front,
                armor_side, armor_rear)
            VALUES
                ({$tank_id}, {$this->tier}, '{$this->name}', {$this->price}, {$this->xp_cost}, {$this->weight},
                {$this->traverse_speed}, {$this->traverse_arc}, {$this->view_range}, {$this->armor_front},
                {$this->armor_side}, {$this->armor_rear})
            ON DUPLICATE KEY UPDATE
                tier = {$this->tier},
                price = {$this->price},
                xp_cost = {$this->xp_cost},
                weight = {$this->weight},
                traverse_speed = {$this->traverse_speed},
                traverse_arc = {$this->traverse_arc},
                view_range = {$this->view_range},
                armor_front = {$this->armor_front},
                armor_side = {$this->armor_side},
                armor_rear = {$this->armor_rear}
        ";
        return $sql;
    }
    
    function getTraverseSpeed()
    {
        return $this->traverse_speed;
    }
    
    function getTraverseArc()
    {
        return $this->traverse_arc;
    }
    
    function getViewRange()
    {
        return $this->view_range;
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
}
?>