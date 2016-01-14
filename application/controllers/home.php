<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();    
        $this->CI->load->helper('date_calc');
    }

    public function index()
    {
		$this->load->view('header');
		$this->load->view('body');
		$this->load->view('footer');
    }

    public function index_json()
    {

        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');

        // do we have valid dates?
        is_date($dateFrom);
        is_date($dateTo);
        
        // convert strings to date objects
        $dateFrom = new DateTime($dateFrom);
        $dateTo = new DateTime($dateTo);

        // include timezone adjustment
        $dateFromTimeZone = $this->input->post('dateFromTimeZone');
        $dateToTimeZone = $this->input->post('dateToTimeZone');

        $dateFrom = gmt_offset_date($dateFromTimeZone,$dateFrom);
        $dateTo = gmt_offset_date($dateToTimeZone,$dateTo);

        // check if dateFrom is earlier than dateTo, if not then swap them over, we are caldulating
        // the difference so this should be fine.
        if( $dateFrom->getTimestamp() > $dateTo->getTimestamp() ){
            $tempDate2 = $dateFrom;
            $dateFrom = $dateTo;
            $dateTo = $tempDate2;
        }

        // build up an objct to return as json
        $numberOfDays = number_of_days($dateFrom, $dateTo);
        $numberOfDaysResults = new stdClass;
        $numberOfDaysResults->days = $numberOfDays;
        $numberOfDaysResults->seconds = convert_time($numberOfDays, "days", "seconds");
        $numberOfDaysResults->minutes = convert_time($numberOfDays, "days", "minutes");
        $numberOfDaysResults->hours = convert_time($numberOfDays, "days", "hours");
        $numberOfDaysResults->years = convert_time($numberOfDays, "days", "years");

        $numberOfWeekDays = number_of_weekdays($dateFrom, $dateTo);
        $numberOfWeekDaysResults = new stdClass;
        $numberOfWeekDaysResults->days = $numberOfWeekDays;
        $numberOfWeekDaysResults->seconds = convert_time($numberOfWeekDays, "days", "seconds");
        $numberOfWeekDaysResults->minutes = convert_time($numberOfWeekDays, "days", "minutes");
        $numberOfWeekDaysResults->hours = convert_time($numberOfWeekDays, "days", "hours");
        $numberOfWeekDaysResults->years = convert_time($numberOfWeekDays, "days", "years");

        $numberOfCompleteWeeks = number_of_complete_weeks($dateFrom, $dateTo);
        $numberOfCompleteWeeksResults = new stdClass;
        $numberOfCompleteWeeksResults->weeks = $numberOfCompleteWeeks;
        $numberOfCompleteWeeksResults->seconds = convert_time($numberOfCompleteWeeks, "weeks", "seconds");
        $numberOfCompleteWeeksResults->minutes = convert_time($numberOfCompleteWeeks, "weeks", "minutes");
        $numberOfCompleteWeeksResults->hours = convert_time($numberOfCompleteWeeks, "weeks", "hours");
        $numberOfCompleteWeeksResults->years = convert_time($numberOfCompleteWeeks, "weeks", "years");

        $dateCalcResults = new stdClass;
        $dateCalcResults->numberOfDays = $numberOfDaysResults;
        $dateCalcResults->numberOfWeekDays = $numberOfWeekDaysResults;
        $dateCalcResults->numberOfCompleteWeeks = $numberOfCompleteWeeksResults;

        // output as json
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($dateCalcResults));

    }
}
