<ul class="post-list-read-later-sns-buttons">
	<li class="post-list-sns-share-button-li post-list-twitter-button-li">
		<a 
			href="https://twitter.com/share"
			class="twitter-share-button"
			data-url="<?php echo esc_url( $permalink ); ?>" 
			<?php echo ( $this->options['seo']['twitter_card_account'] != '' 
				? 'data-via="' . esc_attr( $this->options['seo']['twitter_card_account'] ) . '"' 
				: '' 
			); ?>
			data-hashtags="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			data-text="<?php echo esc_attr( $title ); ?>"
		>Tweet</a>
	</li>

	<li class="post-list-sns-share-button-li post-list-facebook-button-li">
		<div class="fb-share-button" 
			data-href="<?php echo esc_url( $permalink ); ?>" 
			data-layout="button_count"
		></div>
	</li>

	<li class="post-list-sns-share-button-li post-list-googleplus-button-li">
		<div class="g-plus"
			data-action="share" 
			data-annotation="bubble" 
			data-height="20" 
			data-href="<?php echo esc_url( $permalink ); ?>"
		></div>
	</li>

	<li class="post-list-sns-share-button-li post-list-hatena-button-li">
		<a href="http://b.hatena.ne.jp/entry/<?php echo esc_url( $permalink ); ?>"
			class="hatena-bookmark-button"
			data-hatena-bookmark-title="<?php echo esc_attr( $title ); ?>"
			data-hatena-bookmark-layout="standard-balloon"
			data-hatena-bookmark-lang="ja"
			title="このエントリーをはてなブックマークに追加"
		>
			<img src="<?php echo esc_url( SSE_ASSETS_URL ); ?>images/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" />
		</a>
	</li>

	<li class="post-list-sns-share-button-li post-list-pocket-button-li">
		<a href="https://getpocket.com/save"
			class="pocket-btn"
			data-lang="en"
			data-save-url="<?php echo esc_url( $permalink ); ?>"
			data-pocket-count="horizontal" 
			data-pocket-align="left"
		>Pocket</a>
	</li>

	<li class="post-list-sns-share-button-li post-list-line-button-li">
		<a href="http://line.me/R/msg/text/?<?php echo rawurlencode( $title ); ?>%0D%0A<?php esc_url( $permalink ); ?>">
			<img src="<?php echo esc_url( trailingslashit( SSE_ASSETS_URL ) . 'images/linebutton/linebutton_82x20.png' ); ?>" 
				width="82" 
				height="20" 
				alt="LINEで送る" 
			/>
		</a>
	</li>
</ul>