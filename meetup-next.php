<div class="wpsf-next-meetup">
	<h3>Next Meetup</h3>
	<?php
		$meetups = wp_remote_get( 'http://api.meetup.com/2/events?key=f697a7f1a657a3c85b5b342f735142&sign=true&group_id=1174738' );
		$meetups = json_decode( $meetups['body'] );
		
		foreach ( $meetups->results as $meetup_event ) {
			$meetup_name = $meetup_event->name;
			$meetup_url = $meetup_event->event_url;
			$meetup_timestamp = ( $meetup_event->time + $meetup_event->utc_offset ) / 1000;
		}
	?>
	<div class="wpsf-meetup-date"><?php echo esc_html( date( 'M', $meetup_timestamp ) ); ?> <strong><?php echo esc_html( date( 'j', $meetup_timestamp ) ); ?></strong></div>
	<div class="wpsf-meetup-desc">
		<h4><a href="<?php echo esc_url( $meetup_url ); ?>"><?php echo esc_html( $meetup_name ); ?></a></h4>
		<a href="<?php echo esc_url( $meetup_url ); ?>">RSVP &rarr;</a>
	</div>
</div>