<?php get_header(); ?>

	<main role="main">
		<section>

			<article id="post-404">

				<h1><?php _e( 'Page not found', 'thm-tpl' ); ?></h1>
				<h2>
					<a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'thm-tpl' ); ?></a>
				</h2>

			</article>

		</section>
	</main>

<?php get_footer(); ?>
