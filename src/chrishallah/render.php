<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<div <?php echo get_block_wrapper_attributes(); ?> class="prayer-times-widget">

<?php
    $prayer_times = get_islamic_prayer_times(); // Ensure this function exists.

    if ( $prayer_times ) :
    ?>

        <!-- Date Section -->
        <div class="prayer-times-dates">
            <p><strong><?php echo esc_html( $prayer_times['greg-today'] ); ?></strong></p>
            <p><strong><?php echo esc_html( $prayer_times['hijri-today'] ); ?></strong></p>
            <p><strong><?php echo esc_html( $prayer_times['day-name'] ); ?></strong></p>
        </div>

        <!-- Countdown & Next Prayer -->
        <div class="prayer-times-next">
            <p><strong>Next Prayer:</strong> <?php echo esc_html( $prayer_times['next-time'] ); ?></p>
            <p><strong><p id="prayer-countdown">Loading countdown...</p></strong></p>
        </div>

        <!-- Prayer Times List -->
        <div class="prayer-times-list">
            <h3>Prayer Times</h3>
            <ul>
                <li><strong>Fajr:</strong> <?php echo esc_html( $prayer_times['fajr'] ); ?></li>
                <li><strong>Sunrise:</strong> <?php echo esc_html( $prayer_times['sunrise'] ); ?></li>
                <li><strong>Zuhr:</strong> <?php echo esc_html( $prayer_times['zuhr'] ); ?></li>
                <li><strong>Asr:</strong> <?php echo esc_html( $prayer_times['asr'] ); ?></li>
                <li><strong>Maghrib:</strong> <?php echo esc_html( $prayer_times['maghrib'] ); ?></li>
                <li><strong>Isha:</strong> <?php echo esc_html( $prayer_times['isha'] ); ?></li>
            </ul>
        </div>

    <?php else : ?>
        <p class="prayer-times-error"><?php echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' ); ?></p>
    <?php endif; ?>


</div>
