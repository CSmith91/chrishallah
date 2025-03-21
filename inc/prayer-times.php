<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function get_islamic_prayer_times() {
    // $cache_key = 'chrishallah_prayer_times';
    // $cached_data = get_transient( $cache_key );

    // if ( $cached_data ) {
    //     return $cached_data;
    // }


    $api_url = "https://api.aladhan.com/v1/timings/21-03-2025?latitude=51.416665&longitude=-0.1333328&method=3&shafaq=general&tune=5%2C3%2C5%2C7%2C9%2C-1%2C0%2C8%2C-6&timezonestring=UTC&calendarMethod=UAQ";
    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        return false; // API call failed
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( isset( $data['data'] ) ) {
        $prayer_times = [
            'greg-today' => $data['data']['date']['gregorian']['day'] . ' ' . $data['data']['date']['gregorian']['month']['en'] . ' ' . $data['data']['date']['gregorian']['year'],
            'hijri-today' => $data['data']['date']['hijri']['day'] . ' ' . $data['data']['date']['hijri']['month']['en'] . ' ' . $data['data']['date']['hijri']['year'],
            'day-name' => $data['data']['date']['hijri']['weekday']['en'],
            'next-time' => '12:45',
            'countdown' => '2 hours, 3 minutes, 40 seconds',
            'sunrise-today' => $data['data']['timings']['Sunrise'],
            'fajr-init'     => $data['data']['timings']['Fajr'],
            'zuhr-init'    => $data['data']['timings']['Dhuhr'],
            'asr-init'      => $data['data']['timings']['Asr'],
            'maghrib-init'  => $data['data']['timings']['Maghrib'],
            'isha-init'     => $data['data']['timings']['Isha'],
        ];

        //set_transient( $cache_key, $prayer_times, DAY_IN_SECONDS ); // Cache for 24 hours
        return $prayer_times;

    }

    return false;

    // original hardcoded variables
    // return [
    //     'greg-today' => '21st March 2025',
    //     'hijri-today' => '21 Ramadan 1446',
    //     'day-name' => 'Jumuah',
    //     'next-time' => '12:45',
    //     'countdown' => '2 hours, 3 minutes, 40 seconds',
    //     'sunrise-today' => '06:30',
    //     'fajr-init'     => '05:00',
    //     'zuhr-init'    => '12:30',
    //     'asr-init'      => '03:45',
    //     'maghrib-init'  => '06:15',
    //     'isha-init'     => '07:45',
    // ];
}
