<?php
# Action Hook "shapeshifter_header_logo"
# Filter Hook "shapeshifter_filters_header_logo"
global $shapeshifter_theme_mods;

echo '<div id="logo-image-wrapper">' . PHP_EOL;
	echo '<div id="logo-image-inner-wrapper">' . PHP_EOL;
		echo '<div id="logo-image-top-space"></div>' . PHP_EOL;
		echo '<a id="logo-image-wrapper-a" class="u-url" href="' . esc_url( SHAPESHIFTER_SITE_URL ) . '">' . PHP_EOL;
			if ( ! shapeshifter()->get_mobile_detect()->isMobile() ) {
				echo '<div id="logo-image-wrapper-div" class="logo-image-div">' . PHP_EOL;
					echo '<p id="logo-title-description-p" class="logo-p" >' . PHP_EOL;
						echo '<span id="logo-title-span" class="p-name">' . PHP_EOL;
							echo esc_html( SHAPESHIFTER_SITE_NAME ) . PHP_EOL;
						echo '</span>' . PHP_EOL;
						
						echo '<span id="logo-description-span" class="logo-description p-role">' . PHP_EOL;
							echo esc_html( SHAPESHIFTER_SITE_DESCRIPTION ) . PHP_EOL;
						echo '</span>' . PHP_EOL;
					echo '</p>' . PHP_EOL;
				echo '</div>' . PHP_EOL;
			} else {
				if( ! empty( $shapeshifter_theme_mods['header_image_url'] ) )
					echo '<img id="logo-image" class="logo-image" alt="' . esc_attr( SHAPESHIFTER_SITE_NAME ) . '&nbsp;|&nbsp;' . SHAPESHIFTER_SITE_DESCRIPTION . '" src="' . esc_url( ! empty( $shapeshifter_theme_mods['header_image_url'] ) ? $shapeshifter_theme_mods['header_image_url'] : '' ) . '" style="width: 100%;">';
			}
		echo '</a>' . PHP_EOL;
		echo '<div id="logo-image-bottom-space"></div>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
echo '</div>' . PHP_EOL;

?>