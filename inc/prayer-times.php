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

    // Get current date
    $date_today = date('d-m-Y');
    $date_reverse = date('Y-m-d');


    // Make api call
    $api_url = "https://api.aladhan.com/v1/timings/".$date_today."?latitude=51.416665&longitude=-0.1333328&shafaq=general&latitudeAdjustmentMethod=3&tune=5%2C-3%2C-3%2C5%2C1%2C3%2C0%2C-13%2C-6&calendarMethod=UAQ&method=2";
    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        return false; // API call failed
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( isset( $data['data'] ) ) {

        // Convert prayer times to timestamps
        function convert_to_timestamp($time, $date) {
            return strtotime("$date $time");
        }


        // Base prayer times from API
        $base_prayer_times = [
            'sunrise'  => $data['data']['timings']['Sunrise'],
            'fajr'     => $data['data']['timings']['Fajr'],
            'zuhr'     => $data['data']['timings']['Dhuhr'],
            'asr'      => $data['data']['timings']['Asr'],
            'maghrib'  => $data['data']['timings']['Maghrib'],
            'isha'     => $data['data']['timings']['Isha'],
        ];

        // Iqamah times (adjusted prayer times)
        $iqamah_times = [
            'fajr'    => date("H:i", strtotime($base_prayer_times['fajr']) + 1200),  // +20 minutes
            'zuhr'    => date("H:i", strtotime($base_prayer_times['zuhr']) + 900),   // +15 minutes
            'asr'     => date("H:i", strtotime($base_prayer_times['asr']) + 900),    // +15 minutes
            'maghrib' => date("H:i", strtotime($base_prayer_times['maghrib']) + 420), // +7 minutes
            'isha'    => date("H:i", strtotime($base_prayer_times['isha']) + 900),   // +15 minutes
        ];

        // Convert prayer times to timestamps
        $timestamps = [];
        foreach ($iqamah_times as $key => $time) {
            $timestamps["timestamp-$key"] = convert_to_timestamp($time, $date_reverse);
        }

        // Compile final data
        $prayer_times = [
            'greg-today' => $data['data']['date']['gregorian']['day'] . ' ' . $data['data']['date']['gregorian']['month']['en'] . ' ' . $data['data']['date']['gregorian']['year'],
            'hijri-today' => $data['data']['date']['hijri']['day'] . ' ' . $data['data']['date']['hijri']['month']['en'] . ' ' . $data['data']['date']['hijri']['year'],
            'day-name'    => $data['data']['date']['hijri']['weekday']['en'],
            'next-time'   => '12:45',
            'countdown'   => '2 hours, 3 minutes, 40 seconds',
            'prayer_times_base' => $base_prayer_times, 
            'iqamah_times' => $iqamah_times, 
            'timestamps' => $timestamps ];

        // set_transient( $cache_key, $prayer_times, DAY_IN_SECONDS ); // Cache for 24 hours
        return $prayer_times;
    }

    return false;
}

function get_prayer_times_api() {
    $prayer_times = get_islamic_prayer_times(); // Use your existing function

    if (!$prayer_times) {
        return new WP_Error('no_data', 'Prayer times not available', ['status' => 404]);
    }

    return rest_ensure_response([
        // The timestamps for countdown
        'timestamps' => $prayer_times['timestamps'],

        // Formatted prayer times
        'prayer_times_base' => $prayer_times['prayer_times_base'],

        // Formatted iqamah times
        'iqamah_times' => $prayer_times['iqamah_times'],
    ]);
}

add_action('rest_api_init', function() {
    register_rest_route('prayer/v1', '/times', [
        'methods' => 'GET',
        'callback' => 'get_prayer_times_api',
        'permission_callback' => '__return_true',
    ]);
});