<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function get_islamic_prayer_times() {
    // Hardcoded sample prayer times. Replace this with API calls or calculations.
    return [
        'greg-today' => '21st March 2025',
        'hijri-today' => '21 Ramadan 1446',
        'day-name' => 'Jumuah',
        'next-time' => '12:45',
        'countdown' => '2 hours, 3 minutes, 40 seconds',
        'sunrise-today' => '06:30',
        'fajr-init'     => '05:00',
        'zuhr-init'    => '12:30',
        'asr-init'      => '03:45',
        'maghrib-init'  => '06:15',
        'isha-init'     => '07:45',
    ];
}
