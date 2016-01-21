<?php

require_once('CITestCase.php');

class DateCalcHelperTest extends CITestCase
{
    // Load the date calc helper written for this application
    public function setUp()
    {
        $this->CI->load->helper('date_calc');
    }

    // Test for the number of days between two dates
    public function test_number_of_days()
    {
        $dateFrom = '2011-01-01 00:00:00';
        $dateTo = '2012-10-23 11:45:00';
        //convert strings to date objects
        $dateFrom = new DateTime($dateFrom);
        $dateTo = new DateTime($dateTo);

        $this->assertEquals(661,number_of_days($dateFrom, $dateTo));    
    }

    // Test for the number of days between two dates
    public function test_number_of_weekdays()
    {
        $dateFrom = '2011-01-01 00:00:00';
        $dateTo = '2012-10-23 11:45:00';
        //convert strings to date objects
        $dateFrom = new DateTime($dateFrom);
        $dateTo = new DateTime($dateTo);

        $this->assertEquals(472,number_of_weekdays($dateFrom, $dateTo));
    }

    // Test for the number of days between two dates
    public function test_number_of_complete_weeks()
    {
        $dateFrom = '2011-01-01 00:00:00';
        $dateTo = '2012-10-23 11:45:00';
        //convert strings to date objects
        $dateFrom = new DateTime($dateFrom);
        $dateTo = new DateTime($dateTo);

        $this->assertEquals(94,number_of_complete_weeks($dateFrom, $dateTo));
    }

    // Test for conversion of int as a number of days or weeks into seconds, minutes, hours, years.
    public function test_time_conversions()
    {
        $this->assertEquals(172800,convert_time(2, 'days', 'seconds'));
        $this->assertEquals(2880,convert_time(2, 'days', 'minutes'));
        $this->assertEquals(48,convert_time(2, 'days', 'hours'));
        $this->assertEquals(1,convert_time(450, 'days', 'years'));
        $this->assertEquals(1209600,convert_time(2, 'weeks', 'seconds'));
        $this->assertEquals(20160,convert_time(2, 'weeks', 'minutes'));
        $this->assertEquals(336,convert_time(2, 'weeks', 'hours'));
        $this->assertEquals(1,convert_time(60, 'weeks', 'years'));
    }

    // Test to check for date validity.
    public function test_is_date()
    {
        $date = '2011-01-02 00:00:00';
        $this->assertEquals(true,is_date($date));
    }

}
