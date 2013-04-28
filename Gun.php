<?php
class Gun extends Module
{
    protected $ammo;
    
    protected $rate_of_fire;
    
    protected $accuracy;
    
    protected $aim_time;
    
    protected $elevation;
    
    protected $depression;
    
    protected $damage_ap;
    
    protected $damage_gold;
    
    protected $damage_he = 0;
    
    protected $pen_ap;
    
    protected $pen_gold;
    
    protected $pen_he = 0;
    
    protected $price_ap;
    
    protected $price_gold;
    
    protected $price_he = 0;
    
    protected $elite = 0;
    
    function __construct($properties)
    {
        foreach($properties as $key => $value){
            if(property_exists(Gun, $key) || property_exists(Module, $key)){
                $this->$key = $value;   
            }
        }
        parent::__construct($properties);
    }
    
    function toSql($tank_id)
    {
        $sql = "
            INSERT IGNORE INTO `guns`
                (tank_id, tier, name, price, xp_cost, weight,
                ammo, rate_of_fire, accuracy, aim_time,
                elevation, depression, damage_ap, damage_gold,
                damage_he, pen_ap, pen_gold, pen_he,
                price_ap, price_gold, price_he, elite)
            VALUES
                ({$tank_id}, {$this->tier}, '{$this->name}', {$this->price}, {$this->xp_cost}, {$this->weight},
                {$this->ammo}, {$this->rate_of_fire}, {$this->accuracy}, {$this->aim_time},
                {$this->elevation}, {$this->depression}, {$this->damage_ap}, {$this->damage_gold},
                {$this->damage_he}, {$this->pen_ap}, {$this->pen_gold}, {$this->pen_he},
                {$this->price_ap}, {$this->price_gold}, {$this->price_he}, {$this->elite})
            ON DUPLICATE KEY UPDATE
                tier = {$this->tier},
                price = {$this->price},
                xp_cost = {$this->xp_cost},
                weight = {$this->weight},
                ammo = {$this->ammo},
                rate_of_fire = {$this->rate_of_fire},
                accuracy = {$this->accuracy},
                aim_time = {$this->aim_time},
                elevation = {$this->elevation},
                depression = {$this->depression},
                damage_ap = {$this->damage_ap},
                damage_gold = {$this->damage_gold},
                damage_he = {$this->damage_he},
                pen_ap = {$this->pen_ap},
                pen_gold = {$this->pen_gold},
                pen_he = {$this->pen_he},
                price_ap = {$this->price_ap},
                price_gold = {$this->price_gold},
                price_he = {$this->price_he},
                elite = {$this->elite}
        ";
        return $sql;
    }
    
    function toJson()
    {
        json_encode($this);
    }
    
    function getAmmo()
    {
        return $this->ammo;
    }
    
    function setAmmo($ammo)
    {
        $this->ammo = $ammo;
    }
    
    function getRateOfFire()
    {
        return $this->rate_of_fire;   
    }
    
    function getAccuracy()
    {
        return $this->accuracy;
    }
    
    function getAimTime()
    {
        return $this->aim_time;
    }
    
    function getElevation()
    {
        return $this->elevation;
    }
    
    function getDepression()
    {
        return $this->depression;
    }
    
    function getDamageAp()
    {
        return $this->damage_ap;
    }
    
    function getDamageGold()
    {
        return $this->damage_gold;
    }
    
    function getDamageHe()
    {
        return $this->damage_he;
    }
    
    function getPenAp()
    {
        return $this->pen_ap;
    }
    
    function getPenGold()
    {
        return $this->pen_gold;
    }
    
    function getPenHe()
    {
        return $this->pen_he;
    }
    
    function getPriceAp()
    {
        return $this->price_ap;
    }
    
    function getPriceGold()
    {
        return $this->price_gold;
    }
    
    function getPriceHe()
    {
        return $this->price_he;
    }
    
    function getElite()
    {
        return $this->elite;
    }
    
    function setElite($elite)
    {
        $this->elite = $elite;
    }
}
?>