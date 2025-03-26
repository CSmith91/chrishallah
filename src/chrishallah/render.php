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
            <p id="prayer-next">Loading next prayer...</p>
            <p id="prayer-countdown">Loading countdown...</p>
        </div>

        <!-- Prayer Times List -->
		<div class="prayer-times-table">
			<h3>Prayer Times</h3>
			<table>
				<thead>
					<tr>
						<th>Prayer</th>
						<th id="Fajr">Fajr</th>
						<th>Sunrise</th>
						<th id="Zuhr">Zuhr</th>
						<th id="Asr">Asr</th>
						<th id="Maghrib">Maghrib</th>
						<th id="Isha">Isha</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>Starts</strong></td>
						<td id="Fajr-start"><?php echo esc_html($prayer_times['prayer_times_base']['fajr']); ?></td>
						<td><?php echo esc_html($prayer_times['prayer_times_base']['sunrise']); ?></td>
						<td id="Zuhr-start"><?php echo esc_html($prayer_times['prayer_times_base']['zuhr']); ?></td>
						<td id="Asr-start"><?php echo esc_html($prayer_times['prayer_times_base']['asr']); ?></td>
						<td id="Maghrib-start"><?php echo esc_html($prayer_times['prayer_times_base']['maghrib']); ?></td>
						<td id="Isha-start"><?php echo esc_html($prayer_times['prayer_times_base']['isha']); ?></td>
					</tr>
					<tr>
						<td><strong>Iqamah</strong></td>
						<td id="Fajr-iqamah"><?php echo esc_html($prayer_times['iqamah_times']['fajr'] ?? '-'); ?></td>
						<td>-</td> <!-- No Iqamah for Sunrise -->
						<td id="Zuhr-iqamah"><?php echo esc_html($prayer_times['iqamah_times']['zuhr'] ?? '-'); ?></td>
						<td id="Asr-iqamah"><?php echo esc_html($prayer_times['iqamah_times']['asr'] ?? '-'); ?></td>
						<td id="Maghrib-iqamah"><?php echo esc_html($prayer_times['iqamah_times']['maghrib'] ?? '-'); ?></td>
						<td id="Isha-iqamah"><?php echo esc_html($prayer_times['iqamah_times']['isha'] ?? '-'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

    <?php else : ?>
        <p class="prayer-times-error"><?php echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' ); ?></p>
    <?php endif; ?>


</div>
