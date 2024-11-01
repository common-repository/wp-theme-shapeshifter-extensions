w<div class="misc-pub-section">
	<span id="message-disable-publish-button">
		<?php wp_kses( 
			__( 'Disable <b>Publish</b> Button', ShapeShifter_Extensions::TEXTDOMAIN ),
			array(
				'b' => array()
			)
		); ?>
	</span>
	<input type="checkbox" id="disable-publish-button" checked>
</div>