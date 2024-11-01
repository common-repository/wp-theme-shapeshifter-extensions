<ul class="post-list-read-later-sns-share-icons">
	<?php
		$url_encoded_title = urlencode( html_entity_decode( trim( $title ) ) );
		$tUrlEncodedTitle = urlencode( trim( $title . ' #' . get_bloginfo( 'name' ) ) );
	?>
	<li class="post-list-sns-share-icon-li post-list-twitter-share-icon-li"
	>
		<p class="post-list-sns-share-icon-li-p post-list-twitter-share-icon-li-p"
		>
			<a href="<?php echo esc_url( 'https://twitter.com/share?original_referer=' . esc_url( $permalink ) . '&amp;text=' . ( $tUrlEncodedTitle ? $tUrlEncodedTitle : $url_encoded_title ) . '&amp;tw_p=tweetbutton&amp;url=' . esc_url( $permalink ) ); ?>" 
				class="post-list-sns-share-icon-li-p-a post-list-twitter-share-icon-li-p-a" 
				title="twitter" 
				rel="nofollow" 
				target="_blank"
			>
				<i class="fa fa-twitter"></i>
			</a>
		</p>
	</li>

	<?php $url_encoded_title = urlencode( $title ); ?>
	<li class="post-list-sns-share-icon-li post-list-facebook-share-icon-li"
	>
		<p class="post-list-sns-share-icon-li-p post-list-faceook-share-icon-li-p"
		>
			<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink . '&amp;display=popup&amp;t=' . $url_encoded_title ); ?>" 
				class="post-list-sns-share-icon-li-p-a post-list-faceook-share-icon-li-p-a" 
				title="faceook" 
				rel="nofollow" 
				target="_blank"
			>
				<i class="fa fa-facebook"></i>
			</a>
		</p>
	</li>

	<li class="post-list-sns-share-icon-li post-list-googleplus-share-icon-li"
	>
		<p class="post-list-sns-share-icon-li-p post-list-googleplus-share-icon-li-p"
		>
			<a href="<?php echo esc_url( 'https://plus.google.com/share?url=' . esc_url( $permalink ) ); ?>" 
				class="post-list-sns-share-icon-li-p-a post-list-googleplus-share-icon-li-p-a" 
				title="googleplus" 
				rel="nofollow" 
				target="_blank"
			>
				<i class="fa fa-google-plus"></i>
			</a>
		</p>
	</li>

	<?php $url_encoded_title = urlencode( $title ); ?>
	<li class="post-list-sns-share-icon-li post-list-hatena-share-icon-li"
	>
		<p class="post-list-sns-share-icon-li-p post-list-hatena-share-icon-li-p"
		>
			<a href="<?php echo esc_url( 'https://b.hatena.ne.jp/add?mode=confirm&amp;url=' . esc_url( $permalink ) . '&amp;title=' . $url_encoded_title ); ?>" 
				class="post-list-sns-share-icon-li-p-a post-list-hatena-share-icon-li-p-a" 
				title="hatenabookmark" 
				rel="nofollow" 
				target="_blank"
			>
				<span style="font-family: sans-serif;">B!</span>
			</a>
		</p>
	</li>

	<?php $url_encoded_title = urlencode( $title ); ?>
	<li class="post-list-sns-share-icon-li post-list-pocket-share-icon-li"
	>
		<p class="post-list-sns-share-icon-li-p post-list-pocket-share-icon-li-p"
		>
			<a href="<?php echo esc_url( 'https://getpocket.com/edit?url=' . esc_url( $permalink ) . '&amp;title=' . $url_encoded_title ); ?>" 
				class="post-list-sns-share-icon-li-p-a post-list-pocket-share-icon-li-p-a" 
				title="pocket" 
				rel="nofollow" 
				target="_blank"
			>
				<i class="icon-pocket"></i>
			</a>
		</p>
	</li>

	<?php $url_encoded_title = urlencode( $title ); ?>
	<li class="post-list-sns-share-icon-li post-list-line-share-icon-li"
	>
		<p class="post-list-sns-share-icon-li-p post-list-line-share-icon-li-p"
		>
			<a href="<?php echo esc_url( 'https://line.naver.jp/R/msg/text/?' . $urlEncodedTitle . '%0D%0A' . $permalink ); ?>" 
				class="post-list-sns-share-icon-li-p-a post-list-line-share-icon-li-p-a" 
				title="line" 
				rel="nofollow" 
				target="_blank"
			>
				<i class="icon-line"></i>
			</a>
		</p>
	</li>

</ul>