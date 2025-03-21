<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function get_islamic_prayer_times() {
    // Hardcoded sample prayer times. Replace this with API calls or calculations.
    return [
        'fajr'     => '05:00 AM',
        'dhuhr'    => '12:30 PM',
        'asr'      => '03:45 PM',
        'maghrib'  => '06:15 PM',
        'isha'     => '07:45 PM',
    ];
}
