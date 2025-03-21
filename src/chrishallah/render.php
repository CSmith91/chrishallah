<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>>

<?php
        $prayer_times = get_islamic_prayer_times(); // You need to create this function.

        if ( $prayer_times ) {
            echo '<strong>Prayer Times:</strong><br>';
            echo 'Fajr: ' . esc_html( $prayer_times['fajr'] ) . '<br>';
            echo 'Dhuhr: ' . esc_html( $prayer_times['dhuhr'] ) . '<br>';
            echo 'Asr: ' . esc_html( $prayer_times['asr'] ) . '<br>';
            echo 'Maghrib: ' . esc_html( $prayer_times['maghrib'] ) . '<br>';
            echo 'Isha: ' . esc_html( $prayer_times['isha'] );
        } else {
            echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' );
        }
    ?>


</p>
