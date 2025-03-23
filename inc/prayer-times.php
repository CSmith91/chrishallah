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

    // Make api call
    $api_url = "https://api.aladhan.com/v1/timings/".$date_today."?latitude=51.416665&longitude=-0.1333328&method=3&shafaq=general&tune=5%2C3%2C5%2C7%2C9%2C-1%2C0%2C8%2C-6&timezonestring=UTC&calendarMethod=UAQ";
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


        $prayer_times = [
            'greg-today' => $data['data']['date']['gregorian']['day'] . ' ' . $data['data']['date']['gregorian']['month']['en'] . ' ' . $data['data']['date']['gregorian']['year'],
            'hijri-today' => $data['data']['date']['hijri']['day'] . ' ' . $data['data']['date']['hijri']['month']['en'] . ' ' . $data['data']['date']['hijri']['year'],
            'day-name' => $data['data']['date']['hijri']['weekday']['en'],
            'next-time' => '12:45',
            'countdown' => '2 hours, 3 minutes, 40 seconds',
            'sunrise'      => $data['data']['timings']['Sunrise'],
            'fajr'         => $data['data']['timings']['Fajr'],
            'zuhr'        => $data['data']['timings']['Dhuhr'],
            'asr'          => $data['data']['timings']['Asr'],
            'maghrib'      => $data['data']['timings']['Maghrib'],
            'isha'         => $data['data']['timings']['Isha'],
            // 'sunrise'      => convert_to_timestamp($data['data']['timings']['Sunrise'], $date_today),
            // 'fajr'         => convert_to_timestamp($data['data']['timings']['Fajr'], $date_today),
            // 'Zuhr'        => convert_to_timestamp($data['data']['timings']['Dhuhr'], $date_today),
            // 'asr'          => convert_to_timestamp($data['data']['timings']['Asr'], $date_today),
            // 'maghrib'      => convert_to_timestamp($data['data']['timings']['Maghrib'], $date_today),
            // 'isha'         => convert_to_timestamp($data['data']['timings']['Isha'], $date_today),
        ];

        //set_transient( $cache_key, $prayer_times, DAY_IN_SECONDS ); // Cache for 24 hours
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
        'fajr' => strtotime($prayer_times['fajr']),
        'zuhr' => strtotime($prayer_times['zuhr']),
        'asr' => strtotime($prayer_times['asr']),
        'maghrib' => strtotime($prayer_times['maghrib']),
        'isha' => strtotime($prayer_times['isha']),
    ]);
}

add_action('rest_api_init', function() {
    register_rest_route('prayer/v1', '/times', [
        'methods' => 'GET',
        'callback' => 'get_prayer_times_api',
        'permission_callback' => '__return_true',
    ]);
});