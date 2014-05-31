<?php

/**
 * Helper to help you work with date
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

/**
 * Function to return date in the format ISO8601
 *
 * @param  string $day  date in the MySQL date format
 * @param  string $hour time in the MySQL time format
 * @return string
 */
function day_hour_to_ISO8601($day, $hour)
{
    $datetime = new DateTime($day . " " . $hour);
    $dt = $datetime->format(DateTime::ISO8601);
    $dt = substr($dt, 0, strlen($dt) - 5);
    return $dt;
}

/**
 * Transform the brazilian datetime to MySQL datetime
 *
 * @param  string $datetime_ptbr Brazilian datetime
 * @return string
 */
function transform_datetime($datetime_ptbr)
{

    $date_timestamp = date_to_timestamp($datetime_ptbr);
    $mysqldatetime = timestamp_to_mysqldatetime($date_timestamp);

    return $mysqldatetime;
}

/**
 * Verify if the time is valid
 *
 * @param  string $time Time for analisys
 * @return bolean
 */ 
function checktime($time)
{
    $parts = explode(':', $time);
    if ( !isset($parts[0]) || !isset($parts[1]) || !is_numeric($parts[0]) || !is_numeric($parts[1]) )
    {
        return FALSE;
    }
    else
    {
        $hour = $parts[0];
        $minute = $parts[1];
        if ( isset($parts[2]) )
            $second = $parts[2];
    }

    if ( $hour > -1 && $hour < 24 && $minute > -1 && $minute < 60 )
    {
        if ( isset($second) )
        {
            if ( !empty($second) && is_numeric($second) && ($second > -1) && ($second < 60) )
                return TRUE;
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    else
    {
        return FALSE;
    }
}

/**
 * Verify if the received date are in the brazilian date or datetime format
 * if is valid brazilian datetime
 *
 * This parameter $datetime receive full brazilian datetime or only 
 * brazilian date (DD/MM/YYYY HH:MM:SS or DD/MM/YYYY)
 *
 * @param  string $datetime_ptbr Date for analisys
 * @return bolean
 */ 
function is_datetime($datetime_ptbr)
{
    $datetime_part = explode(" ", $datetime_ptbr);
    // if the string datetime_ptbr have time
    if ( isset($datetime_part[1]) )
    {
        $parts = explode("/", $datetime_part[0]);
        if ( !isset($parts[0]) || !isset($parts[1]) || !isset($parts[2]) )
        {
            return FALSE;
        }
        else
        {
            $day = $parts[0];
            $month = $parts[1];
            $year = $parts[2];
        }

        $time_part = explode(":", $datetime_part[1]);
        if ( !isset($time_part[0]) || !isset($time_part[1]) )
        {
            return FALSE;
        }

        if ( !empty($day) && !empty($month) && !empty($year) )
        {
            if ( !is_numeric($day) || !is_numeric($month) || !is_numeric($year) )
            {
                return FALSE;
            }
            if ( checkdate($month, $day, $year) && checktime($datetime_part[1]) )
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    else
    {
        $parts = explode("/", $datetime_part[0]);
        if ( !isset($parts[0]) || !isset($parts[1]) || !isset($parts[2]) )
        {
            return FALSE;
        }
        else
        {
            $day = $parts[0];
            $month = $parts[1];
            $year = $parts[2];
        }

        if ( !empty($day) && !empty($month) && !empty($year) )
        {
            if ( !is_numeric($day) || !is_numeric($month) || !is_numeric($year) )
            {
                return FALSE;
            }
            if ( checkdate($month, $day, $year) )
            {
                return TRUE;
            }
        }
        else
        {
            return FALSE;
        }
    }
}

/**
 * Function to transform the mysql_date to brazilian date 
 * 
 * This parameter $datetime receive full datetime or only date (AAAA-MM-DD HH:MM:SS or AAAA-MM-DD) 
 * or (AAAA/MM/DD HH:MM:SS or AAAA/MM/DD)
 *
 * @param  string $datetime Date in the format "AAAA/MM/DD" or "AAAA-MM-DD"
 * @return string
 */
function brazilian_datetime($datetime)
{

    if ( !empty($datetime) )
    {

        // transform_datetime() define the date in portuguese for the date format MySQL
        $datetime_part = explode(" ", $datetime);
        $datetime_part[0] = str_replace("-", "/", $datetime_part[0]);
        // if the string datetime_ptbr have time
        if ( isset($datetime_part[1]) )
        {
            $parts = explode("/", $datetime_part[0]);
            list ($hour, $minute, $second) = explode(":", $datetime_part[1]);
            if ( $parts[2] > 31 )
            {
                return($parts[0] . "/" . $parts[1] . "/" . $parts[2] . ' ' . $hour . ':' . $minute . ':' . $second);
            }
            else
            {
                return($parts[2] . "/" . $parts[1] . "/" . $parts[0] . ' ' . $hour . ':' . $minute . ':' . $second);
            }
        }
        else
        {
            $parts = explode("/", $datetime_part[0]);
            if ( $parts[2] > 31 )
            {
                return($parts[0] . "/" . $parts[1] . "/" . $parts[2]);
            }
            else
            {
                return($parts[2] . "/" . $parts[1] . "/" . $parts[0]);
            }
        }
    }
    else
    {
        return FALSE;
    }
}

function sum_date($datetime, $year, $month, $day)
{
    $datetime_part = explode(" ", $datetime);
    $datetime_part[0] = str_replace("-", "/", $datetime_part[0]);
    $date = explode("/", $datetime_part[0]);
    //debug($date);
    $newData = date("Y/m/d", mktime(0, 0, 0, $date[1] + $month,
                            $date[2] + $day, $date[0] + $year));
    if ( isset($datetime_part[1]) )
        return $newData . ' ' . $datetime_part[1];
    else
        return $newData;
}

/**
 * breaks down the elements of a brazilian date
 * 
 * @param  string $data date in the format dd/mm/yyyy
 * @return array
 */
function brazilian_date_explode($data)
{
    $parts = explode("/", brazilian_datetime($data));
    $saida = array
        (
        'day' => $parts[0],
        'month' => $parts[1],
        'year' => $parts[2],
    );
    return($saida);
}

/**
 * Compare two brazilian dates
 * 
 * return 2 if the dates are equal
 * return 1 if date1 is greather than date2
 * return 0 if date2 is greather than date1
 *
 * @param  string $xdata1 date in the format dd/mm/yyyy
 * @param  string $xdata2 date in the format dd/mm/yyyy
 * @return int|boolean could be an int, could be a boolean
 */
function brazilian_date_compare($xdate1, $xdate2)
{
    if ( is_datetime($xdate1) && is_datetime($xdate2) )
    {
        $date1 = brazilian_date_explode($xdate1);
        $date2 = brazilian_date_explode($xdate2);
        $timestamp1 = gmmktime(0, 0, 0, $date1['month'], $date1['day'], $date1['year']);
        $timestamp2 = gmmktime(0, 0, 0, $date2['month'], $date2['day'], $date2['year']);
        if ( $timestamp1 == $timestamp2 )
            return(2);
        $r = (($timestamp1 > $timestamp2) ? 1 : 0);
        return($r);
    }
    else
    {
        return FALSE;
    }
}

/**
 * Convert MySQL's DATE (YYYY-MM-DD) or DATETIME (YYYY-MM-DD hh:mm:ss) to timestamp
 *
 * Returns the timestamp equivalent of a given DATE/DATETIME
 *
 * @todo add regex to validate given datetime
 * @author Clemens Kofler <clemens.kofler@chello.at>
 * @access    public
 * @return    integer
 */
function mysqldatetime_to_timestamp($datetime = "")
{
    // function is only applicable for valid MySQL DATETIME (19 characters) and DATE (10 characters)
    $l = strlen($datetime);
    if ( !($l == 10 || $l == 19) )
        return 0;

    //
    $date = $datetime;
    $hours = 0;
    $minutes = 0;
    $seconds = 0;

    // DATETIME only
    if ( $l == 19 )
    {
        list($date, $time) = explode(" ", $datetime);
        list($hours, $minutes, $seconds) = explode(":", $time);
    }

    list($year, $month, $day) = explode("-", $date);

    return mktime($hours, $minutes, $seconds, $month, $day, $year);
}

/**
 * Convert MySQL's DATE (YYYY-MM-DD) or DATETIME (YYYY-MM-DD hh:mm:ss) to date using given format string
 *
 * Returns the date (format according to given string) of a given DATE/DATETIME
 *
 * @author Clemens Kofler <clemens.kofler@chello.at>
 * @access    public
 * @return    integer
 */
function mysqldatetime_to_date($datetime = "", $format = "d/m/Y H:i:s")
{
    return date($format, mysqldatetime_to_timestamp($datetime));
}

/**
 * Convert timestamp to MySQL's DATE or DATETIME (YYYY-MM-DD hh:mm:ss)
 *
 * Returns the DATE or DATETIME equivalent of a given timestamp
 *
 * @author Clemens Kofler <clemens.kofler@chello.at>
 * @access    public
 * @return    string
 */
function timestamp_to_mysqldatetime($timestamp = "", $datetime = true)
{
    if ( empty($timestamp) || !is_numeric($timestamp) )
        $timestamp = time();

    return ($datetime) ? date("Y-m-d H:i:s", $timestamp) : date("Y-m-d", $timestamp);
}

/**
 * Convert timestamp to Human Date
 *
 * Returns the date (format according to given string) of a given timestamp
 *
 * @author    Cleiton Francisco V. Gomes <http://www.cleiton.net/>
 * @access    public
 * @param     string
 * @param     string
 * @return    string
 */
function timestamp_to_date($timestamp = "", $format = "d/m/Y H:i:s")
{
    if ( empty($timestamp) || !is_numeric($timestamp) )
        $timestamp = time();
    return date($format, $timestamp);
}

/**
 * Convert Human Date to Timestamp
 *
 * Returns the timestamp equivalent of a given HUMAN DATE/DATETIME
 *
 * @author    Cleiton Francisco V. Gomes <http://www.cleiton.net/>
 * @access    public
 * @param     string
 * @return    integer
 */
function date_to_timestamp($datetime = "")
{
    if ( !preg_match("/^(\d{1,2})[.\- \/](\d{1,2})[.\- \/](\d{2}(\d{2})?)( (\d{1,2}):(\d{1,2})(:(\d{1,2}))?)?$/", $datetime, $date) )
        return FALSE;

    $day = $date[1];
    $month = $date[2];
    $year = $date[3];
    $hour = (empty($date[6])) ? 0 : $date[6];
    $min = (empty($date[7])) ? 0 : $date[7];
    $sec = (empty($date[9])) ? 0 : $date[9];

    return mktime($hour, $min, $sec, $month, $day, $year);
}

/**
 * Convert HUMAN DATE to MySQL's DATE or DATETIME (YYYY-MM-DD hh:mm:ss)
 *
 * Returns the DATE or DATETIME equivalent of a given HUMAN DATE/DATETIME
 *
 * @author    Cleiton Francisco V. Gomes <http://www.cleiton.net/>
 * @access    public
 * @param     string
 * @param     boolean
 * @return    string
 */
function date_to_mysqldatetime($date = "", $datetime = TRUE)
{
    return timestamp_to_mysqldatetime(date_to_timestamp($date), $datetime);
}