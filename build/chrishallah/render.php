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
						<th></th> <!-- Empty top-left corner -->
						<th>Fajr</th>
						<th>Sunrise</th>
						<th>Zuhr</th>
						<th>Asr</th>
						<th>Maghrib</th>
						<th>Isha</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>Starts</strong></td>
						<td><?php echo esc_html($prayer_times['fajr']); ?></td>
						<td><?php echo esc_html($prayer_times['sunrise']); ?></td>
						<td><?php echo esc_html($prayer_times['zuhr']); ?></td>
						<td><?php echo esc_html($prayer_times['asr']); ?></td>
						<td><?php echo esc_html($prayer_times['maghrib']); ?></td>
						<td><?php echo esc_html($prayer_times['isha']); ?></td>
					</tr>
					<tr>
						<td><strong>Iqadah</strong></td>
						<td><?php echo esc_html($prayer_times['iqadah_fajr'] ?? '-'); ?></td>
						<td>-</td> <!-- No Iqadah for Sunrise -->
						<td><?php echo esc_html($prayer_times['iqadah_zuhr'] ?? '-'); ?></td>
						<td><?php echo esc_html($prayer_times['iqadah_asr'] ?? '-'); ?></td>
						<td><?php echo esc_html($prayer_times['iqadah_maghrib'] ?? '-'); ?></td>
						<td><?php echo esc_html($prayer_times['iqadah_isha'] ?? '-'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

    <?php else : ?>
        <p class="prayer-times-error"><?php echo esc_html__( 'Prayer times are not available at the moment.', 'chrishallah' ); ?></p>
    <?php endif; ?>


</div>
