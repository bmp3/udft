<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php

            global $kmwp;

		    $kmwp = udft::correct_global_var();
            wp_head();

        ?>

	</head>
	<body <?php body_class(); ?>>

		<div class="wrapper">

			<header class="header clear" role="banner">

					<div class="logo">
						<a href="<?php echo home_url(); ?>">
							<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Logo" class="logo-img">
						</a>
					</div>

                    <div class="slider-box">
                        <?php echo do_shortcode('[metaslider id="7"]'); ?>
                    </div>

					<nav class="nav" role="navigation">
						<?php wp_nav_menu(); ?>
					</nav>

			</header>

            <?php

                global $udft;
                $header_layout = udft::start_content_layout( false );

                echo

                '<div class="content-body-wrap ' . $udft['box-type'] . '">
    
                    <div class="content-wrap row">';

                    echo $header_layout;


            ?>