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
        </div>

        <!-- Countdown & Next Prayer -->
        <div class="prayer-times-next">
            <p id="prayer-next">Loading next prayer...</p>
            <p id="prayer-countdown">Loading countdown...</p>
        </div>

        <!-- Prayer Times List -->
		<div class="prayer-times-table">
			<div class="prayer-grid">
				<div class="prayer-cell" id="Fajr-cell">
					<!-- <img src="fajr.png" alt="Fajr" class="prayer-icon"> -->
					<span class="prayer-name">Fajr</span>
					<span class="prayer-time">Start<br><?php echo esc_html($prayer_times['prayer_times_base']['fajr']); ?></span>
					<span class="prayer-iqamah">Iqamah<br><?php echo esc_html($prayer_times['iqamah_times']['fajr'] ?? '-'); ?></span>
				</div>

				<div class="prayer-cell" id="Sunrise-cell">
					<!-- <img src="sunrise.png" alt="Sunrise" class="prayer-icon"> -->
					<span class="prayer-name">Sunrise</span>
					<span class="prayer-time"><?php echo esc_html($prayer_times['prayer_times_base']['sunrise']); ?></span>
				</div>

				<div class="prayer-cell" id="Zuhr-cell">
					<!-- <img src="zuhr.png" alt="Zuhr" class="prayer-icon"> -->
					<span class="prayer-name">Zuhr</span>
					<span class="prayer-time">Start<br><?php echo esc_html($prayer_times['prayer_times_base']['zuhr']); ?></span>
					<span class="prayer-iqamah">Iqamah<br><?php echo esc_html($prayer_times['iqamah_times']['zuhr'] ?? '-'); ?></span>
				</div>

				<div class="prayer-cell" id="Asr-cell">
					<!-- <img src="asr.png" alt="Asr" class="prayer-icon"> -->
					<span class="prayer-name">Asr</span>
					<span class="prayer-time">Start<br><?php echo esc_html($prayer_times['prayer_times_base']['asr']); ?></span>
					<span class="prayer-iqamah">Iqamah<br><?php echo esc_html($prayer_times['iqamah_times']['asr'] ?? '-'); ?></span>
				</div>

				<div class="prayer-cell" id="Maghrib-cell">
					<!-- <img src="maghrib.png" alt="Maghrib" class="prayer-icon"> -->
					<span class="prayer-name">Maghrib</span>
					<span class="prayer-time">Start<br><?php echo esc_html($prayer_times['prayer_times_base']['maghrib']); ?></span>
					<span class="prayer-iqamah">Iqamah<br><?php echo esc_html($prayer_times['iqamah_times']['maghrib'] ?? '-'); ?></span>
				</div>

				<div class="prayer-cell" id="Isha-cell">
					<!-- <img src="isha.png" alt="Isha" class="prayer-icon"> -->
					<span class="prayer-name">Isha</span>
					<span class="prayer-time">Start<br><?php echo esc_html($prayer_times['prayer_times_base']['isha']); ?></span>
					<span class="prayer-iqamah">Iqamah<br><?php echo esc_html($prayer_times['iqamah_times']['isha'] ?? '-'); ?></span>
				</div>
			</div>


    <?php else : ?>
        <p class="prayer-times-error"><?php echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' ); ?></p>
    <?php endif; ?>


</div>
