var controlIds = {};
( function( $ ) {
	$( document ).ready( function() {
		// スライドバーを追加
		$( 'input.shapeshifter-input-text-associated-with-range' ).each( function( index ) {
			$( '<input class="input-range ' + $( this ).data( 'class' ) + '" type="range" min="' + $( this ).data( 'min' ) + '" max="' + $( this ).data( 'max' ) + '" step="' + $( this ).data( 'step' ) + '" value="' + $( this ).data( 'value' ) + '" style="width:100%;">' ).insertAfter( this );
		});
		$( 'input.input-range' ).on( 'input', function( e ) {
			e.preventDefault();
			if( $( this ).hasClass( 'opacity' ) ) {
				$( this ).prev().val( $( this ).val() );
				$( this ).prev().change();
			} else if( $( this ).hasClass( 'border-image-slice' ) ) {
				$( this ).prev().val( $( this ).val() + 'px' );
				$( this ).prev().change();
			} else if( $( this ).hasClass( 'background-size' ) ) {
				$( this ).prev().val( $( this ).val() + 'px' );
				$( this ).prev().change();
			} else {
				$( this ).prev().val( $( this ).val() + 'px' );
				$( this ).prev().change();
			}
		});

		$( '.shapeshifter-input-radio-text' ).each( function( index ) {

			currentSetting = $( this ).parent().parent();
			firstLabel = currentSetting.find( 'label:first' );
			currentInput = firstLabel.find( 'input' );
			currentValue = currentInput.val();
			
			settingId = currentInput.data( 'customize-setting-link' );

			// テキストボックス
			html = '<label><input type="radio" value="' + currentValue + '" name="radio-' + settingId + '" class="shapeshifter-input-hidden-associated-with-radio"><input type="text" class="shapeshifter-input-text-associated-with-radio" value="' + currentValue + '" style="width:90%;"><br></label>';
			$( currentSetting ).append( html );
			// 選択肢
			$( '.radio-' + settingId ).each( function( index ) {
				html = '<label><input type="radio" value="' + $( this ).val() + '" name="radio-' + settingId + '" class="shapeshifter-input-hidden-associated-with-radio">' + $( this ).data( 'text' ) + '<br></label>';
				$( currentSetting ).append( html );
			});

			currentSetting.find( '.shapeshifter-input-hidden-associated-with-radio[value="' + currentInput.data( 'value' ) + '"]' ).click().change();

		});
		$( 'input.shapeshifter-input-text-associated-with-radio' ).on( 'input', function( e ) {
			e.preventDefault();
			changedValue = $( this ).val();
			prevInputRadio = $( this ).prev();
			prevInputRadio.val( changedValue );
			//console.log( prevInputRadio );
			if( prevInputRadio[ 0 ].checked ) {
				currentHiddenInput = $( this ).parent().parent().find( 'label:first input.shapeshifter-input-radio-text' );
				currentHiddenInput.val( changedValue ).click().change();
			}
		} )
		$( 'input.shapeshifter-input-hidden-associated-with-radio' ).on( 'change', function( e ) {
			e.preventDefault();
			changedValue = $( this ).val();
			currentHiddenInput = $( this ).parent().parent().find( 'label:first input.shapeshifter-input-radio-text' );
			currentHiddenInput.val( changedValue ).click().change();
		});
		
	});
}) ( jQuery );
