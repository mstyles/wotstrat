<?php
mysql_connect("localhost", "wot_admin", "poop");
mysql_select_db("wot_data");

include_once('../simple_html_dom.php');

$url = 'http://wiki.worldoftanks.com';
$html = file_get_html($url);

//$nation = $html->find('#By_Nation', 0)->parent()->parent()->parent()->find('.NavContent a', 5);
scrapeNation($nation);

$tank_name = 'M10_Wolverine';
scrapeTank($tank_name);

//foreach ($nations as $nation){
//    scrapeNation($nation);
//}

/*--------------------------------------*/


//$image_url = 'http://wiki.worldoftanks.com/images/9/9e/Germany-G_Panther.png';
//if(copy($image_url, 'gw_panther.png')){
//    echo 'Success';
//} else {
//    echo 'Fail';
//}

function scrapeNation($nation_link)
{
    $url = 'http://wiki.worldoftanks.com';
    $nation_url = $url.$nation_link->href;

    $nation_html = file_get_html($nation_url);
    
    //$tank_list = '/T1_Cunningham/M2_Light_Tank/T2_Light_Tank/T1E6/M3_Stuart/M22_Locust/MTLS-1G14/M5_Stuart/M24_Chaffee/T21/T71/T2_Medium_Tank/M2_Medium_Tank/M3_Lee/M4_Sherman/M7/Ram-II/M4A2E4/M4A3E2_Sherman_Jumbo/M4A3E8_Sherman/T20/M26_Pershing/T26E4_Super_Pershing';
    
    $been_scraped = explode('/', $tank_list);
    foreach ($nation_html->find('.NavFrame a') as $tank_link){
        echo $tank_link->href."<br>";
        
        $tank_name = str_replace('/', '', $tank_link->href);
        if(!in_array($tank_name, $been_scraped)){
            if($tank_link->class == 'image') continue;
            scrapeTank($tank_name);
        }
        
        echo $tank_link->href;
    }
}

function scrapeTank($tank_name)
{
    if($tank_link->href == '/T23') return;
    $url = 'http://wiki.worldoftanks.com/'.$tank_name;
    $html = file_get_html($url);
    
    $tank = new Tank(parseTank($html));
    
    $data_tables = getDataTables($html->find('table.moduleTable'));
    
    populateModulesFromData($tank, $data_tables);
    
    $tank->generateSqlAndInsert();
    
    echo $tank->getName().' processed successfully!'.'<br>';
    
    $html->__destruct();
    $tank->__destruct();
    $html = null;
    $tank = null;
    $data_tables = null;
    $tank_link = null;
    unset($html);
    unset($tank);
    unset($data_tables);
    unset($tank_link);
}

function getDataTables($moduleTables)
{
    $data_tables = array();
    foreach($moduleTables as $moduleTable){
        $headers = $moduleTable->find('tr th');
        $columns = array();
        foreach($headers as $header){
            $columns[] = $header->innertext;
        }
        
        
        $j = 0;
        $table_object = array();
        $row_elems = $table_object = $moduleTable->find('tbody tr');
        foreach($row_elems as $row_elem){
            $row = array();
            
            $tds = $row_elem->find('td');
            $i = 0;
            foreach($tds as $td){
                $spans = $td->find('span[style=display:none]');
                foreach($spans as $span){
                    $span->innertext = '';
                }
                $row_name = $columns[$i++];
                $row[$row_name] = $td->plaintext;
            }
            $table_object[$j++] = $row;
        }
        $data_tables[] = $table_object;
    }
    return $data_tables;
}

function populateModulesFromData(&$tank, $data_tables)
{
    foreach($data_tables as $data_table){
        //print_r($data_table);
        foreach($data_table as $data_row){
            if($data_row['Range']){
                $tank->addRadio(parseRadio($data_row));
            } else if ($data_row['Damage']){
                $tank->addGun(parseGun($data_row));
                //$guns[] = parseGun($data_row);
            } else if ($data_row['Power']){
                $tank->addEngine(parseEngine($data_row));
            } else if ($data_row['Load Limit']){
                $tank->addSuspension(parseSuspension($data_row));
            } else if ($data_row['Traverse Speed']){
                $tank->addTurret(parseTurret($data_row));
            }
        }
    }
}

function getColumns($headers){
    print_r('get column: '.$header->innertext."<br>");
    return $header->innertext;
}

function processUpgrades($str)
{
    preg_match("/([\S]+)100%/", $str, $matches);
    return array_pop($matches);
}

function stripDegrees($str)
{
    $str = preg_replace("/[^\d\/-]/", '', $str);
    return $str;
}

function processDigit($digit)
{
    preg_match("/[\d\.]+/", $digit, $matches);
    $val = $matches[0];
    return $val;
}

function processTriplet($triplet)
{
    $triplet = str_replace(' ', '', $triplet);
    preg_match("/[\d\.]+(\/[\d\.]+)*/", $triplet, $matches);
    $values = explode('/', $matches[0]);
    return $values;
}

function convertRomanNum($num)
{
    //echo 'before: ';
    //var_dump($num);
    //exit();
    //echo '<br>after: <br>';
    $num = trim($num);
    switch ($num){
        case 'I':
            return 1;
        case 'II':
            return 2;
        case 'III':
            return 3;
        case 'IV':
            return 4;
        case 'V':
            return 5;
        case 'VI':
            return 6;
        case 'VII':
            return 7;
        case 'VIII':
            return 8;
        case 'IX':
            return 9;
        case 'X':
            return 10;
        default:
            return 'RomanNumeral Error';
    }
}

function processXp($xp)
{
    $xp = html_entity_decode($xp);
    $xp = str_replace(array('-', ',', '?'), '', $xp);
    return $xp ? $xp : 0;
}

function processWeight($wgt)
{
    $wgt = html_entity_decode($wgt);
    $wgt = str_replace(',', '', $wgt);
    preg_match("/[\S]+/", $wgt, $matches);
    return $matches[0] ? $matches[0] : 0;
}

function processPrice($prc)
{
    $prc = html_entity_decode($prc);
    $prc = str_replace(array(',','-'), '', $prc);
    $prc = trim($prc);
    return $prc ? $prc : 0;
}

function parseTurret($obj)
{
    foreach($obj as $prop => $val){
            //print_r($prop . ": " . $val);
            //continue;
            $key = strtolower($prop);
            $key = str_replace(' ', '_', $key);
            switch($prop){
                    case 'Name':
                            $obj[$key] = html_entity_decode($obj[$prop]);
                            break;
                    case 'Tier':
                            //04IV
                            $obj[$key] = convertRomanNum($obj[$prop]);
                            break;
                    case 'Armor':
                            //38/25/25 mm
                            $values = processTriplet($obj[$prop]);
                            $obj['armor_front'] = (int)$values[0];
                            $obj['armor_side'] = (int)$values[1];
                            $obj['armor_rear'] = (int)$values[2];
                            break;
                    case 'Traverse Speed':
                            //40 d/s
                            $value = processUpgrades($obj[$prop]);
                            $value = $value ? $value : processDigit($obj[$prop]);
                            $obj[$key] = $value;
                            break;
                    case 'Traverse Arc':
                            //360�
                            $obj[$key] = stripDegrees($obj[$prop]);
                            break;
                    case 'View Range':
                            //370 m
                            $value = processUpgrades($obj[$prop]);
                            $value = $value ? $value : processDigit($obj[$prop]);
                            $obj[$key] = $value;
                            break;
                    case 'XP Cost':
                            //0------ or ---
                            $obj[$key] = processXp($obj[$prop]);
                            break;
                    case 'Price':
                            //4,500
                            $obj[$key] = processPrice($obj[$prop]);
                            break;
                    case 'Weight':
                            //1,700 kg
                            $obj[$key] = processWeight($obj[$prop]);
                            break;
                    default:
            }
            unset($obj[$prop]);
    }
    
    $turret = new Turret($obj);
    
    return $turret;
}

function parseGun($obj)
{
    foreach($obj as $prop => $val){
            //print_r($prop . ": " . $obj[$prop]);
            //continue;
            $key = strtolower($prop);
            $key = str_replace(' ', '_', $key);
            switch($prop){
                    case 'Tier':
                            //04IV
                            $obj[$key] = convertRomanNum($val);
                            break;
                    case 'Name':
                            $obj[$key] = html_entity_decode($obj[$prop]);
                            break;
                    case 'Ammo':
                            $value = str_replace('?', '', $obj[$prop]);
                            $value = $value ? $value : 0;
                            $obj[$key] = $value;
                            break;
                    case 'Damage':
                            //110/110/175 HP
                            //var_dump($obj[$prop]);
                            $values = processTriplet($obj[$prop]);
                            $obj['damage_ap'] = $values[0];
                            $obj['damage_gold'] = $values[1] ? $values[1] : 0;
                            $obj['damage_he'] = $values[2] ? $values[2] : 0;
                            break;
                    case 'Penetration':
                            //96/143/38 mm
                            $values = processTriplet($obj[$prop]);
                            $obj['pen_ap'] = $values[0];
                            $obj['pen_gold'] = $values[1] ? $values[1] : 0;
                            $obj['pen_he'] = $values[2] ? $values[2] : 0;
                            break;
                    case 'Shell Price':
                            //56 /7 /56
                            $obj[$prop] = html_entity_decode($obj[$prop]);
                            $obj[$prop] = str_replace(array(',', ' '), '', $obj[$prop]);
                            $values = explode('/', $obj[$prop]);
                            $obj['price_ap'] = $values[0];
                            $obj['price_gold'] = $values[1] ? $values[1] : 0;
                            $obj['price_he'] = $values[2] ? $values[2] : 0;
                            break;
                    case 'Rate of Fire':
                            //15 r/m
                            $value = processUpgrades($obj[$prop]);
                            $value = $value ? $value : processDigit($obj[$prop]);
                            $obj[$key] = $value;
                            break;
                    case 'Accuracy':
                            //0.4 m
                            $value = processUpgrades($obj[$prop]);
                            $value = $value ? $value : processDigit($obj[$prop]);
                            $obj[$key] = $value;
                            break;
                    case 'Aim Time':
                            //2.1 s
                            $value = processUpgrades($obj[$prop]);
                            $value = $value ? $value : processDigit($obj[$prop]);
                            $obj[$key] = $value;
                            break;
                    case 'Elevation':
                            //-10�/+15�
                            $obj[$prop] = stripDegrees($obj[$prop]);
                            $value = array_shift(explode('/', $obj[$prop]));
                            if($value == '-' || !$value) $value = 0;
                            $obj['depression'] = $value;
                            $value = array_pop(explode('/', $obj[$prop]));
                            $value = $value ? $value : 0;
                            $obj[$key] = $value;
                            break;
                    case 'XP Cost':
                            //0------ or ---
                            $obj[$key] = processXp($obj[$prop]);
                            break;
                    case 'Price':
                            //4,500
                            $obj[$key] = processPrice($obj[$prop]);
                            break;
                    case 'Weight':
                            //1,700 kg
                            $obj[$key] = processWeight($obj[$prop]);
                            break;
                    default:
            }
            unset($obj[$prop]);
    }
    
    $gun = new Gun($obj);
    
    return $gun;
}


function parseEngine($obj)
{
    foreach($obj as $prop => $val){
            //print_r($prop . ": " . $val);
            //continue;
            $key = strtolower($prop);
            $key = str_replace(' ', '_', $key);
            switch($prop){
                    case 'Name':
                            $obj[$key] = html_entity_decode($obj[$prop]);
                            break;
                    case 'Tier':
                            //04IV
                            $obj[$key] = convertRomanNum($obj[$prop]);
                            break;
                    case 'Power':
                            //300 hp
                            $obj[$key] = processDigit($obj[$prop]);
                            break;
                    case 'Fire Chance':
                            //20 %
                            $obj[$key] = processDigit($obj[$prop]);
                            break;
                    case 'Type':
                            $obj[$key] = $obj[$prop];
                            break;
                    case 'XP Cost':
                            //0------ or ---
                            $obj[$key] = processXp($obj[$prop]);
                            break;
                    case 'Price':
                            //4,500
                            $obj[$key] = processPrice($obj[$prop]);
                            break;
                    case 'Weight':
                            //1,700 kg
                            $obj[$key] = processWeight($obj[$prop]);
                            break;
                    default:
            }
            unset($obj[$prop]);
    }
    
    $engine = new Engine($obj);
    
    return $engine;
}

function parseSuspension($obj)
{
    foreach($obj as $prop => $val){
            //print_r($prop . ": " . $val);
            //continue;
            $key = strtolower($prop);
            $key = str_replace(' ', '_', $key);
            switch($prop){
                    case 'Name':
                            $obj[$key] = html_entity_decode($obj[$prop]);
                            break;
                    case 'Tier':
                            //04IV
                            $obj[$key] = convertRomanNum($obj[$prop]);
                            break;
                    case 'Load Limit':
                            //18.3 t
                            $obj[$key] = array_shift(explode(' ', $obj[$prop]));
                            break;
                    case 'Traverse Speed':
                            //42 d/s
                            $obj[$key] = processDigit($obj[$prop]);
                            break;
                    case 'XP Cost':
                            //0------ or ---
                            $obj[$key] = processXp($obj[$prop]);
                            break;
                    case 'Price':
                            //4,500
                            $obj[$key] = processPrice($obj[$prop]);
                            break;
                    case 'Weight':
                            //1,700 kg
                            $obj[$key] = processWeight($obj[$prop]);
                            break;
                    default:
            }
            unset($obj[$prop]);
    }
    
    $suspension = new Suspension($obj);
    
    return $suspension;
}


function parseRadio($obj)
{
    //echo 'Radio!'.'<br>';
    foreach($obj as $prop => $val){
            //print_r($prop . ": " . $obj[$prop]);
            //continue;
            $key = strtolower($prop);
            $key = str_replace(' ', '_', $key);
            switch($prop){
                    case 'Name':
                            $obj[$key] = html_entity_decode($obj[$prop]);
                            break;
                    case 'Tier':
                            //04IV
                            $obj[$key] = convertRomanNum($obj[$prop]);
                            break;
                    case 'Range':
                            //395 m
                            $value = processUpgrades($obj[$prop]);
                            $value = $value ? $value : processDigit($obj[$prop]);
                            $obj[$key] = $value;
                            break;
                    case 'XP Cost':
                            //0------ or ---
                            $obj[$key] = processXp($obj[$prop]);
                            break;
                    case 'Price':
                            //4,500
                            $obj[$key] = processPrice($obj[$prop]);
                            break;
                    case 'Weight':
                            //1,700 kg
                            $obj[$key] = processWeight($obj[$prop]);
                            break;
                    default:
            }
            unset($obj[$prop]);
    }
    
    $radio = new Radio($obj);
    
    return $radio;
}

function parseTank($html)
{
    $tank_data;
    $main_panel = $html->find('div.Tank',0);
    
    //$name = $main_panel->find('h3', 0)->find('span', 0)->innertext;
    $name_span = $main_panel->find('h3', 0)->find('span', 0);
    if($name_span->find('img', 0)){
        $name_span->find('img', 0)->outertext = '';
    }
    $name = $name_span->innertext;
    $name = preg_replace("/&#?[a-z0-9]+;/i","",$name);
    $tank_data['name'] = trim(html_entity_decode($name));
    
    $nation = $main_panel->find('table', 0)->find('td', 0)->innertext;
    $tank_data['nation'] = strtolower($nation);
    
    $class = $main_panel->find('table', 0)->find('td', 1)->innertext;
    $class = str_replace(array(' Tank', 'Turreted '), '', $class);
    $tank_data['class'] = strtolower($class);
    
    $tier = $main_panel->find('table', 0)->find('td', 2)->innertext;
    $tier = str_replace('Tier ', '', $tier);
    $tank_data['tier'] = convertRomanNum($tier);
    
    $panel = $main_panel->find('table', 1);
    foreach($panel->find('tr th') as $header){
        $prop = $header->innertext;
        $value = '';
        $key = strtolower($prop);
        $key = str_replace(' ', '_', $key);
        switch($prop){
            case 'Cost':
                //"687,550  "
                $value = $header->next_sibling();
                $value = html_entity_decode($value);
                $value = str_replace(array(',', '.'), '', $value);
                $value = processDigit($value);
                $tank_data['price'] = $value ? $value : 0;
                break;
            case 'Hit Points':
                //530 HP
                foreach($header->next_sibling()->find('span') as $span){
                    $value .= $span->innertext.'|';
                }
                $value = html_entity_decode($value);
                $value = str_replace(',', '', $value);
                $value = explode('|', $value);
                $tank_data['hp_stock'] = $value[0];
                $tank_data['hp_elite'] = $value[1];
                break;
            case 'Weight Limit':
                $value = $header->next_sibling()->find('span', 0)->innertext;
                $value = array_shift(explode('/', $value));
                $tank_data['stock_weight'] = $value;
                break;
            case 'Crew':
                $value = $header->parent()->next_sibling()->first_child()->innertext;
                $value = preg_replace("/\s\(.+?\)/", '', $value);
                $value = str_replace("<br />", ',', $value);
                $tank_data[$key] = $value;
                break;
            case 'Speed Limit':
                //56 km/h
                $value = $header->next_sibling();
                $tank_data[$key] = processDigit($value);
                break;
            case 'Pivot':
                $value = $header->next_sibling()->find('span', 0)->innertext;
                $tank_data[$key] = $value == 'Yes' ? 1 : 0;
                break;
            case 'Hull Armor':
                //25/25/19 mm
                $value = $header->next_sibling();
                $values = processTriplet($value);
                $tank_data['armor_front'] = (int)$values[0];
                $tank_data['armor_side'] = (int)$values[1];
                $tank_data['armor_rear'] = (int)$values[2];
                break;
            case 'View Range':
                $td = $header->next_sibling();
                $td->find('span', 1)->outertext = '';
                $div = $td->find('div', 0);
                $value = $div->innertext;
                if(empty($value)){
                    $value = $td->find('span', 0)->innertext;
                } else if(strlen($value) > 3){
                    $value = $div->find('text', 0);
                }
                $tank_data[$key] = $value;
                break;
            case 'Gun Traverse Speed':
                $td = $header->next_sibling();
                
                $span = $td->find('span', 1)->outertext = '';
                $value = $td->find('div', 0)->innertext;
                $value = $value ? $value : $td->find('span', 0)->innertext;
                $tank_data[$key] = processDigit($value);
                break;
            case 'Gun Arc':
                $value = $header->next_sibling();
                foreach($value->find('span') as $span){
                    $span->outertext = '';
                }
                $value = stripDegrees($value);
                $values = explode('/', $value);
                $tank_data['gun_arc_left'] = $values[0] ? $values[0] : 0;
                $tank_data['gun_arc_right'] = $values[1] ? $values[1] : 0;
                break;
            default:
        }
    }
    return $tank_data;
}

function __autoload($class_name)
{
    include $class_name . '.php';
}
?>