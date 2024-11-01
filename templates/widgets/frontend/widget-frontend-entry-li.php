<?php $class_prefix = esc_attr( $class_prefix ); ?>
<li class="<?php echo $class_prefix; ?> widget-entry" style="border:none;">
	<a class="<?php echo $class_prefix; ?>-a widget-entry-a" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" style="width: 100%;">
		<div class="<?php echo $class_prefix; ?>-thumbnail widget-entry-thumbnail">

			<?php 
			$thumbnailURL = esc_url( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );

			if( $thumbnailURL ) {
				
				$class = esc_attr( 'widget-entry-thumbnail-img ' . $class_prefix . '-thumbnail-img' );
				$args = array(
					'class' => $class,
					'alt' => esc_attr( $post->post_title ? $post->post_title : '' ),
					'title' => esc_attr( $post->post_title ? $post->post_title : '' ),
					'data-style' => 'background-size: 80px 80px; background-position: center center; background-repeat: no-repeat;'
				);
				echo apply_filters( 'shapeshifter_filter_widget_entry_thumbnail_image', get_the_post_thumbnail( $post->ID, 'shapeshifter-thumb80', $args ) );
				
			} else {
				
				$class = 'widget-entry-def-thumbnail-img ' . $class_prefix . '-def-thumbnail-img';

				$default_cat_thumbnail = esc_url( self::shapeshifter_get_the_default_thumbnail_url( $post ) );

				echo self::shapeshifter_get_default_thumbnail_div_tag( $class, array( 'width' => '80px', 'height' => '80px' ), $default_cat_thumbnail );

			}
			?>

		</div>
		<?php if( $display_date ) { ?>
		<div class="<?php echo $class_prefix; ?>-date widget-entry-date">
			<span><?php 
				echo '<span class="post-date">';
					echo '<i class="fa fa-clock-o"></i>';
					echo SHAPESHIFTER_NBSP;
					echo '<time class="dt-published entry-date" datetime="' . esc_attr( get_the_time( 'c', $post ) ) . '">' . esc_html( get_the_time( 'Y/m/d', $post ) ) . '</time>';
				echo '</span>';
			?></span>
		</div>
		<?php } ?>

		<div class="<?php echo $class_prefix; ?>-title widget-entry-title">
			<span><?php echo esc_html( mb_strlen( $post->post_title ) > 30 ? mb_substr( $post->post_title, 0, 30 ) . '...' : $post->post_title ); ?></span>
		</div>

		<?php if( $display_excerpt ) { ?>
			<div class="<?php echo $class_prefix; ?>-excerpt widget-entry-excerpt"><?php 
				//echo wp_strip_all_tags( sse_get_the_excerpt( $post->post_content, 100 ) );
				//esc_html_e( '... Read more', ShapeShifter_Extensions::TEXTDOMAIN ); 
			?></div>
		<?php } ?>

	</a>
</li>
