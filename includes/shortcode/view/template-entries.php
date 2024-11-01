<div class="shapeshifter-entries-wrapper shapeshifter-<?php echo esc_attr( $type ); ?>-wrapper sp-slide">

	<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>" class="shapeshifter-entry-a shapeshifter-new-entry-a">

	<?php if( $atts['slide-type'] == 'standard' ) { ?>

		<?php if( $atts['is-thumbnail-on'] != '' ) { ?>
			<div class="shapeshifter-entries-thumbnail-wrapper shapeshifter-new-entries-thumbnail-wrapper" style="">
				<?php if( has_post_thumbnail( $post->ID ) ) { ?>
					<img class="shapeshifter-entries-thumbnail-img shapeshifter-new-entries-thumbnail-img" src="<?php echo esc_url( get_the_post_thumbnail_url( $post ) ); ?>" width="200" height="150">
				<?php } else { ?>
					<img class="shapeshifter-entries-thumbnail-img shapeshifter-new-entries-thumbnail-img" src="<?php echo esc_url( ShapeShifter_Other_Methods::shapeshifter_get_the_default_thumbnail_url( $post ) ); ?>" width="200" height="150">
				<?php } ?>
			</div>
		<?php } ?>

		<div class="shapeshifter-entries-title-wrapper shapeshifter-new-entries-title-wrapper">
			<p class="shapeshifter-entries-title shapeshifter-new-entries-title"><?php echo esc_html( 
					mb_strlen( get_the_title( $post->ID ) ) > 50
					? mb_substr( get_the_title( $post->ID ), 0, 49 ) . '...'
					: get_the_title( $post->ID )
				); ?></p>
		</div>

		<?php if( intval( $atts['excerpt-number'] ) > 0 ) { ?>
			<div class="shapeshifter-entries-description-wrapper shapeshifter-new-entries-description-wrapper">
				<p class="shapeshifter-entries-description shapeshifter-new-entries-description"><?php echo esc_html( 
					mb_strlen( str_replace( array( "\n", "\r", "&nbsp;", '"', "'" ), '', wp_strip_all_tags( $post->post_content ) ) ) > intval( $atts['excerpt-number'] )
					? sse_get_the_excerpt( $post->post_content, intval( $atts['excerpt-number'] ) )
					: str_replace( array( "\n", "\r", "&nbsp;", '"', "'" ), '', wp_strip_all_tags( $post->post_content ) )
				); ?></p>
			</div>
		<?php } ?>

	<?php } elseif( $atts['slide-type'] == 'standard' ) { ?>

	<?php } ?>
	</a>

</div>
