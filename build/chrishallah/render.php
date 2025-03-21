<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>>

<?php
        $prayer_times = get_islamic_prayer_times(); // You need to create this function.

        if ( $prayer_times ) {
			echo esc_html__( $prayer_times['greg-today']) . '<br>';
			echo esc_html__( $prayer_times['hijri-today']) . '<br>';
			echo esc_html__( $prayer_times['day-name']) . '<br>';
			echo esc_html__( $prayer_times['next-time']) . '<br>';
			echo esc_html__( $prayer_times['countdown']) . '<br>';
            echo '<strong>Prayer Times:</strong><br>';
            echo 'Fajr: ' . esc_html( $prayer_times['fajr-init'] ) . '<br>';
			echo '<i>Sunrise</i>: ' . esc_html( $prayer_times['sunrise-today'] ) . '<br>';
            echo 'Zuhr: ' . esc_html( $prayer_times['zuhr-init'] ) . '<br>';
            echo '\'Asr: ' . esc_html( $prayer_times['asr-init'] ) . '<br>';
            echo 'Maghrib: ' . esc_html( $prayer_times['maghrib-init'] ) . '<br>';
            echo '\'Isha: ' . esc_html( $prayer_times['isha-init'] );
        } else {
            echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' );
        }
    ?>


</p>
