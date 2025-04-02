<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// allows us to convert todays date in the hijri format
function convert_to_hijri($gregorian_date) {
    $formatter = new IntlDateFormatter(
        "en@calendar=islamic",
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'UTC',
        IntlDateFormatter::TRADITIONAL
    );

    $timestamp = strtotime($gregorian_date);
    $hijri_date = $formatter->format($timestamp);

    // Remove "AH" and commas
    $hijri_date = preg_replace('/AH|\s*,/', '', $hijri_date);

    // Swap "Shawwal 4 1446" → "4 Shawwal 1446"
    if (preg_match('/(\w+)\s+(\d+)\s+(\d+)/', $hijri_date, $matches)) {
        $hijri_date = "{$matches[2]} {$matches[1]} {$matches[3]}";
    }

    return trim($hijri_date);
}

function get_islamic_prayer_times() {
    // $cache_key = 'chrishallah_prayer_times';
    // $cached_data = get_transient( $cache_key );

    // if ( $cached_data ) {
    //     return $cached_data;
    // }

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
        
        // jamat times, // converted to iqamah by adding 10 mins to jamat
        $iqamah_to_jamat_delay = 600;
        $iqamah_times = [
            // 'fajr'    => $data['fajr_jamat'],  
            // 'zuhr'    => $data['dhuhr_jamat'],   
            // 'asr'     => $data['asr_jamat'],    
            // 'maghrib' => $data['magrib_jamat'], 
            // 'isha'    => $data['isha_jamat'],
            'fajr'    => date("H:i", strtotime($data['fajr_jamat'])),  
            'zuhr'    => date("H:i", strtotime($data['dhuhr_jamat'])),   
            'asr'     => date("H:i", strtotime($data['asr_jamat']) + $iqamah_to_jamat_delay),    
            'maghrib' => date("H:i", strtotime($data['magrib_jamat'])), 
            'isha'    => date("H:i", strtotime($data['isha_jamat'])),
        ];

        // Convert prayer times to timestamps
        $timestamps = [];
        foreach ($iqamah_times as $key => $time) {
            $timestamps["timestamp-$key"] = strtotime("{$data['date']} $time");
        }
        
        // Convert Gregorian date to Hijri
        $hijri_date = convert_to_hijri($data['date']);

        // Compile final data
        $prayer_times = [
            'greg-today' => date('jS F Y', strtotime($data['date'])),
            'hijri-today' => $hijri_date,
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