( function( $ ) { $( document ).ready( function() {
// ウィジェット
	// スイッチ
	$( '.new_entries.shapeshifter-switch-select-tab-li > a' ).css({ 'opacity': 1 });
	$( '.shapeshifter-switch-select-tab-li' ).click( function( e ) {
		e.preventDefault();
		// すべてのタブのaタグ
		tabsA = $( this ).parent().find( 'li a' );
		// 選択されたタブから名前を取得
		selectName = $( this ).data( 'selected-name' );
		// ウィジェットのラッパーを取得
		parent =  $( this ).parent().parent();
		// ボックスすべて
		divBoxes = $( parent ).find( '.shapeshifter-switch-selected-div' );
		// 選択されたタブ名のボックス
		selectedBox = parent.find( '.shapeshifter-switch-selected-div-' + selectName );

		// タブのCSS変化
		$( tabsA ).css({ 'opacity': 0.3 });
		$( this ).find( 'a' ).css({ 'opacity': 1 });
		// ボックスのCSS変化
		$( divBoxes ).css({'display':'none'});
		$( selectedBox ).css({'display':'block'});

	});

}); }) ( jQuery );