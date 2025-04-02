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

    require_once __DIR__ . '/../config.php'; // Load environment variables
    $api_key = $_ENV['API_KEY']; // Now available globally

    // Make api call
    //$api_url = "https://api.aladhan.com/v1/timings/".$date_today."?latitude=51.416665&longitude=-0.1333328&shafaq=general&latitudeAdjustmentMethod=3&tune=5%2C-3%2C-3%2C5%2C1%2C3%2C0%2C-13%2C-6&calendarMethod=UAQ&method=2";
    $api_url = "https://www.londonprayertimes.com/api/times/?format=json&key=".$api_key."&24hours=true";

    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        return false; // API call failed
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( isset( $data ) ) {

        // Convert prayer times to timestamps
        function convert_to_timestamp($time, $date) {
            return strtotime("$date $time");
        }

        // Base prayer times from API
        $base_prayer_times = [
            'fajr'     => $data['fajr'],
            'sunrise'  => $data['sunrise'],
            'zuhr'     => $data['dhuhr'],
            'asr'      => $data['asr'],
            'maghrib'  => $data['magrib'],
            'isha'     => $data['isha'],
        ];
        
        // jamat times, // converted to iqamah
        $iqamah_to_jamat_delay = 600;
        $iqamah_times = [
            'fajr'    => date("H:i", strtotime($data['fajr_jamat']) + $iqamah_to_jamat_delay),  
            'zuhr'    => date("H:i", strtotime($data['dhuhr_jamat']) + $iqamah_to_jamat_delay),   
            'asr'     => date("H:i", strtotime($data['asr_jamat']) + $iqamah_to_jamat_delay),    
            'maghrib' => date("H:i", strtotime($data['magrib_jamat']) + $iqamah_to_jamat_delay), 
            'isha'    => date("H:i", strtotime($data['isha_jamat']) + $iqamah_to_jamat_delay),
        ];

        // Convert prayer times to timestamps
        $timestamps = [];
        foreach ($iqamah_times as $key => $time) {
            $timestamps["timestamp-$key"] = strtotime("{$data['date']} $time");
        }

        // Compile final data
        $prayer_times = [
            // 'greg-today' => $data['data']['date']['gregorian']['day'] . ' ' . $data['data']['date']['gregorian']['month']['en'] . ' ' . $data['data']['date']['gregorian']['year'],
            // 'hijri-today' => $data['data']['date']['hijri']['day'] . ' ' . $data['data']['date']['hijri']['month']['en'] . ' ' . $data['data']['date']['hijri']['year'],
            // 'day-name'    => $data['data']['date']['hijri']['weekday']['en'],
            'prayer_times_base' => $base_prayer_times, 
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