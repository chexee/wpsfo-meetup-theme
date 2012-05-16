<div class="wpsf-next-meetup">
	<h3>Next Meetup</h3>
	<div class="wpsf-meetup-date"><?php echo esc_html( wpsfo_get_meetup( 'month' ) ); ?> <strong><?php echo esc_html( wpsfo_get_meetup( 'day' ) ); ?></strong></div>
	<div class="wpsf-meetup-desc">
		<h4><a href="<?php echo esc_url( wpsfo_get_meetup( 'url' ) ); ?>"><?php echo esc_html( wpsfo_get_meetup( 'name' ) ); ?></a></h4>
		<a href="<?php echo esc_url( wpsfo_get_meetup( 'url' ) ); ?>">RSVP &rarr;</a>
	</div>
</div>