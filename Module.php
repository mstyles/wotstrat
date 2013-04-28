<?php
class Module
{
    protected $tier;
    
    protected $name;
    
    protected $price;
    
    protected $xp_cost;
    
    protected $weight;
    
    function __construct($properties)
    {
        foreach($properties as $key => $value){
            if(property_exists(Module, $key)){
                $this->$key = $value;    
            }
        }
    }
    
    function getTier()
    {
        return $this->tier;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function getPrice()
    {
        return $this->price;
    }
    
    function getXpCost()
    {
        return $this->xp_cost;
    }
    
    function getWeight()
    {
        return $this->weight;
    }
}
?>