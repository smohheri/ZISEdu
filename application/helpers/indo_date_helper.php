<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('indo_date')) {
    function indo_date($date, $withDayName = FALSE)
    {
        $date = trim((string) $date);
        if ($date === '' || $date === '0000-00-00') {
            return '-';
        }

        $timestamp = strtotime($date);
        if ($timestamp === FALSE) {
            return $date;
        }

        $months = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        $dayNames = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        );

        $day = date('j', $timestamp);
        $month = (int) date('n', $timestamp);
        $year = date('Y', $timestamp);

        $formatted = $day . ' ' . $months[$month] . ' ' . $year;
        if ($withDayName) {
            $dayKey = date('D', $timestamp);
            $formatted = $dayNames[$dayKey] . ', ' . $formatted;
        }

        return $formatted;
    }
}
