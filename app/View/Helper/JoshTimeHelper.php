<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JoshTimeHelper
 *
 * @author etbmx
 */
class JoshTimeHelper extends AppHelper{
    public function parseDate($dateArray){
        foreach($dateArray as $key=>$val){
            $$key = $val;
        }
        if(!empty($day) && !empty($month) && !empty($year)){
            return strtotime("$month/$day/$year");
        }else{
            if(empty($day) && !empty($month) && !empty($year)){
                return strtotime("$month/$year");
            }else{
                if(empty($day) && empty($month) && !empty($year)){
                    return strtotime($year);
                }else{
                    return null;
                }
            }
        }   
    }
    
    public function format($date, $formatString = 'd M Y'){
        if($date == null || !is_numeric($date)){
            return null;
        }else{
            return date($formatString, $date);
        }
    }
    
    

}

?>
