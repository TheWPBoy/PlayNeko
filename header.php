<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PlayNeko
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-wp-interactive="playneko">
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'playneko' ); ?></a>

	<!-- Search Overlay -->
	<div id="search-overlay" class="search-overlay">
		<div class="search-overlay-content">
			<button id="search-close" class="search-close">&times;</button>
			<div class="search-form-container">
				<h3><?php _e('Search', 'playneko'); ?></h3>
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>

	<header id="masthead" class="new-head">
		<div class="logo-container">
						<?php if ( has_custom_logo() ) : ?>
							<?php the_custom_logo(); ?>
						<?php else : ?>
							<a href="<?php echo home_url(); ?>" data-wp-interactive="">
							<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
							</a>
						<?php endif; ?>
				</div>
				<div class="search-container">
					<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
						<input type="search" class="search-field" placeholder="Search …" value="" name="s">
					</form>
				</div>
		<div class="menu-container">
		<a class="menu-item" href="<?php echo home_url('/model/'); ?>"  aria-label="Models"  title="Models" data-wp-interactive="">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4"/><path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z"/></g></svg>
				<span class="text-lebel">Models</span>
			</a>
			<a class="menu-item" href="<?php echo home_url('/category/'); ?>" aria-label="Categories"  title="Categories" data-wp-interactive="">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 10c0-3.771 0-5.657 1.172-6.828S7.229 2 11 2h2c3.771 0 5.657 0 6.828 1.172S21 6.229 21 10v4c0 3.771 0 5.657-1.172 6.828S16.771 22 13 22h-2c-3.771 0-5.657 0-6.828-1.172S3 17.771 3 14z"/><path stroke-linecap="round" d="M8 12h8M8 8h8m-8 8h5"/></g></svg>
				<span class="text-lebel">Categories</span>
			</a>
			<a class="menu-item" href="<?php echo home_url('/'); ?>" aria-label="Home"  title="Home" data-wp-interactive="">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m12.857 4.06l5.866 4.817c.33.27.527.686.527 1.13v8.803c0 .814-.638 1.44-1.383 1.44H15.25V15.5a2.75 2.75 0 0 0-2.75-2.75h-1a2.75 2.75 0 0 0-2.75 2.75v4.75H6.133c-.745 0-1.383-.626-1.383-1.44v-8.802c0-.445.197-.86.527-1.13l5.866-4.819a1.34 1.34 0 0 1 1.714 0m5.01 17.69c1.61 0 2.883-1.335 2.883-2.94v-8.802a2.96 2.96 0 0 0-1.075-2.29L13.81 2.9a2.84 2.84 0 0 0-3.618 0L4.325 7.718a2.96 2.96 0 0 0-1.075 2.29v8.802c0 1.605 1.273 2.94 2.883 2.94z"/></svg>
				<span class="text-lebel">Home</span>
			</a>
			<a class="menu-item" href="#" id="search-button" aria-label="Search"  title="Search">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11.5" cy="11.5" r="9.5"/><path stroke-linecap="round" d="M18.5 18.5L22 22M9 11.5h2.5m0 0H14m-2.5 0V14m0-2.5V9"/></g></svg>
				<span class="text-lebel">Search</span>
			</a>
			<a class="menu-item" href="#" id="menu-button" aria-label="Menu"  title="Menu">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M3 4h18v2H3zm6 7h12v2H9zm-6 7h18v2H3z"/></svg>
			<span class="text-lebel">Menu</span>
			</a>
			<!-- Mobile Menu Dropdown -->
			<div id="mobile-menu-dropdown" class="mobile-menu-dropdown">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
						'menu_id'        => 'mobile-primary-menu',
						'container'      => false,
						'fallback_cb'    => false,
				)
			);
			?>
			</div>
		</div>
	</header>

