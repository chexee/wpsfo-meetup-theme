<?php
/**
 * Header template.
 *
 * @package P2
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '&laquo;', true, 'right' ); ?> <?php bloginfo( 'name' ); ?></title>
<script type="text/javascript" src="http://use.typekit.com/bit8ces.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="header">
<?php do_action( 'before' ); ?>

	<div class="sleeve">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<?php if ( get_bloginfo( 'description' ) ) : ?>
			<small><?php bloginfo( 'description' ); ?></small>
		<?php endif; ?>
		
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
		
		<a class="secondary" href="<?php echo home_url( '/' ); ?>"></a>

		<?php if ( current_user_can( 'publish_posts' ) ) : ?>
			<a href="" id="mobile-post-button" style="display: none;"><?php _e( 'Post', 'p2' ) ?></a>
		<?php endif; ?>
	</div>

	<?php if ( has_nav_menu( 'primary' ) ) : ?>
	<div role="navigation" class="site-navigation main-navigation">
		<h1 class="assistive-text"><?php _e( 'Menu', 'p2' ); ?></h1>
		<div class="assistive-text skip-link"><a href="#main" title="<?php esc_attr_e( 'Skip to content', 'p2' ); ?>"><?php _e( 'Skip to content', 'p2' ); ?></a></div>

		<?php wp_nav_menu( array(
			'theme_location' => 'primary',
			'fallback_cb'    => '__return_false',
		) ); ?>
	</div>
	<?php endif; ?>
</div>

<div id="wrapper">

	<?php get_sidebar(); ?>