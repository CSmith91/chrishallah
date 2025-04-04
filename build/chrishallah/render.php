<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<div <?php echo get_block_wrapper_attributes(); ?> class="prayer-times-widget">

<?php
    $prayer_times = get_islamic_prayer_times(); // Ensure this function exists.

    if ( $prayer_times ) :

		$labels = [
			'start' => 'Start',  // Change this to 'Begins' in one place if needed
			'iqamah' => 'Iqamah'
		];
		
		$prayers = [
			'Fajr' => ['fajr', true],
			'Sunrise' => ['sunrise', false],
			'Zuhr' => ['zuhr', true],
			'Asr' => ['asr', true],
			'Maghrib' => ['maghrib', true],
			'Isha' => ['isha', true]
		];
    ?>



        <!-- Date Section -->
        <div class="prayer-times-dates">
			<p><strong><?php echo esc_html( $prayer_times['greg-today'] ); ?> • <?php echo esc_html( $prayer_times['hijri-today'] ); ?></strong></p>
        </div>

        <!-- Countdown & Next Prayer -->
        <div class="prayer-times-next">
            <p id="prayer-next">Loading next prayer...</p>
            <p id="prayer-countdown">Loading countdown...</p>
        </div>

        <!-- Prayer Times List -->
		<div class="prayer-times-table">
			<div class="prayer-grid">
				<?php foreach ($prayers as $name => [$key, $hasIqamah]) : ?>
					<div class="prayer-cell" id="<?php echo esc_attr($name); ?>-cell">
						<!-- <img src="<?php echo esc_attr($key); ?>.png" alt="<?php echo esc_attr($name); ?>" class="prayer-icon"> -->
						<span class="prayer-name"><?php echo esc_html($name); ?></span>
						<span class="prayer-time">
							<?php echo esc_html($labels['start']); ?><br>
							<?php echo esc_html($prayer_times['prayer_times_base'][$key]); ?>
						</span>
						<?php if ($hasIqamah) : ?>
							<span class="prayer-iqamah">
								<?php echo esc_html($labels['iqamah']); ?><br>
								<?php echo esc_html($prayer_times['iqamah_times'][$key] ?? '-'); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>


    <?php else : ?>
        <p class="prayer-times-error"><?php echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' ); ?></p>
    <?php endif; ?>


</div>
