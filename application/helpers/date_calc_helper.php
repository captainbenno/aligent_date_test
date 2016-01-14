<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Various date calculation helpers

if ( ! function_exists('number_of_days'))
{
    // calculate the amount of days between two dates
    function number_of_days($date1, $date2)
    {
        // calc number and return
        $datediff = $date2->getTimestamp() - $date1->getTimestamp();
        return intval(floor($datediff/(60*60*24)));
    }   
}

if ( ! function_exists('number_of_weekdays'))
{
    // calculate the amount of weekdays between two dates
    function number_of_weekdays($date1, $date2)
    {
        // calc number and return
        $weekdays = 0;
        $workingDays = [1, 2, 3, 4, 5]; 
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($date1, $interval ,$date2);
        foreach($daterange as $date){
            if (in_array($date->format('N'), $workingDays)){
                $weekdays++;
            }
        }
        return $weekdays;
    }   
}

if ( ! function_exists('number_of_complete_weeks'))
{   
    // calculate the amount of complete weeks between two dates
    function number_of_complete_weeks($date1, $date2)
    {
        // calc number and return
        return intval(floor($date1->diff($date2)->days/7));
    }   
}

if ( ! function_exists('convert_time'))
{
    // convert the datepart (days or weeks) passed in as an integer to another format
    function convert_time($datePart, $datePartFrom, $datePartTo)
    {
        $validDatePartFrom = ['weeks', 'days'];
        $validDatePartTo = ['seconds', 'minutes', 'hours', 'years'];

        if(! is_int($datePart)) {
            throw new InvalidArgumentException('$datePart is not a valid integer');            
        }

        if(! in_array($datePartFrom, $validDatePartFrom)) {
            throw new InvalidArgumentException('$datePartTo is not in the list of valid date parts - weeks, days');            
        }

        if(! is_int($datePart)) {
            throw new InvalidArgumentException('$datePartTo is not in the list of valid date parts - seconds, minutes, hours, years');            
        }

        // convert $datePart to seconds as a base to work everything else from
        if( $datePartFrom == 'days'){
            $datePartSeconds = $datePart*24*60*60;
        } else {
            // assume weeks
            $datePartSeconds = $datePart*7*24*60*60;
        }

        // convert seconds to minutes,hours or years
        switch ($datePartTo) {
            case 'minutes':
                return intval(floor($datePartSeconds/60));
            case 'hours':
                return intval(floor($datePartSeconds/60/60));
            case 'years':
                return intval(floor($datePartSeconds/60/60/24/365));
            default: // return seconds by default
                return $datePartSeconds;
        }
    }   
}

if ( ! function_exists('is_date'))
{
    // check to see if string is a valid date
    function is_date($date2Check)
    { 
        $date = date_parse($date2Check);
        if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"])){
            return true;
        } else {
            throw new InvalidArgumentException('Argument is not a valid date');
            return false;
        }
    }   
}

if ( ! function_exists('gmt_offset_date'))
{
    // add the gmt offset to the date passed in, offset must me in gmt format eg: +08:30
    function gmt_offset_date($gmtTimeZone,$date)
    { 
        $modifier = substr($gmtTimeZone, 0,1);
        if( $modifier == "+" || $modifier == '-'){
            // split the gmtTimeZone into modifier, hours and minutes
            $timeOffset = str_replace($modifier, '', $gmtTimeZone);
            $hoursOffset = intval(explode(':',$timeOffset)[0]);
            $minsOffset = intval(explode(':',$timeOffset)[1]);
            // create a total minutes offset to apply to the date
            $totalMinsOffset = ($hoursOffset*60)+$minsOffset;
            if( $modifier == "+"){
               $date->add( new DateInterval('PT'.$totalMinsOffset.'M'));
            } else {
                $date->sub( new DateInterval('PT'.$totalMinsOffset.'M'));
            }
            return $date;
        } else {            
            throw new InvalidArgumentException('Argument is not a valid gmt marker');
            return false;            
        }

    }   
}