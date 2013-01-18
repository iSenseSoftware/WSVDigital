<?php
/**
 * This helper is used for parsing and formatting dates in a friendly-fashion
 *
 * @author Joshua McKenzie <joshua.mckenzie@bayer.com>
 */
class JoshTimeComponent extends Component{

  /*
   * Used to parse the array created by the CakePHP Form helper's date input
   * into an integer date
   * 
   * @param $dateArray integer[] The date array produced by CakePHP from a set 
   * of select elements used to input dates.  Formated as:
   * ['Date']=>
   *          ['day']=>integer,
   *          ['month']=>integer,
   *          ['year']=>integer
   *  
   * @return integer|null Returns null if date is unparseable
   * 
   */
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
    
    /*
     * A wrapper for the standard php date() function
     * 
     * @param integer $date The date to be formatted
     * 
     * @param string $formatString A formatting string as described in the PHP manual:
     * http://php.net/manual/en/function.date.php
     * 
     * @return null|string
     */
    
    public function format($date, $formatString = 'd M Y'){
        if($date == null || !is_numeric($date)){
            return null;
        }else{
            return date($formatString, $date);
        }
    }
    

}

?>
