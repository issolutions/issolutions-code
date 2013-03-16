( function( $ ){

	

	wp.customize('wallpress_theme_options[heading_font]', function( value ) {
		value.bind( function( to ) {
			if(to=='') {
				$('#heading_font').html('');
				return}
			var font = to.split('|');
		
			$('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family='+font[1]+'" type="text/css" />');
			if($('#heading_font').length<1){
				$('head').append('<style id="heading_font" type="text/css" media="screen"></style>')
			}
			$('#heading_font').html('h1,h2,h3,h4,h5 {' +font[0]+' }');
			setTimeout(function(){$(window).trigger('resize');},500);
		} );
	} );
	wp.customize('wallpress_theme_options[primary_font]', function( value ) {
		value.bind( function( to ) {

			if(to=='') {
				$('#primary_font').html('');
				return}
			var font = to.split('|');
		
			$('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family='+font[1]+'" type="text/css" />');
			if($('#primary_font').length<1){
				$('head').append('<style id="primary_font" type="text/css" media="screen"></style>')
			}
			$('#primary_font').html('body{' +font[0]+' }');
			setTimeout(function(){$(window).trigger('resize');},500);
			
		} );
	} );
	wp.customize('wallpress_theme_options[custom_code]', function( value ) {
		value.bind( function( to ) {
			
			if($('#style_cus').length<1){
				$('head').append('<style type="text/css" id="style_cus" media="screen"></style>');	
			}		
			$('#style_cus').html(to);
			
			setTimeout(function(){$(window).trigger('resize');},500);
			
		} );
	} );

	wp.customize('wallpress_theme_options[custom_js]', function( value ) {
		value.bind( function( to ) {
			
			
		} );
	} );

	// wp.customize('wallpress_theme_options[logo_custom_type]', function( value ) {
	// 	value.bind( function( to ) {
	// 		$('#site-title a').attr('class',to);
			
	// 		if($('#style_cus').length<1){
	// 			$('head').append('<style type="text/css" id="style_cus" media="screen"></style>');	
	// 		}	
	// 		if(to == 'logo_image'){	
	// 			$('#style_cus').text('#branding a {	color: #000;}');
	// 		}
			
	// 		setTimeout(function(){$(window).trigger('resize');},500);
	// 	} );
	// } );
	wp.customize('wallpress_theme_options[logo_custom_title]', function( value ) {
		value.bind( function( to ) {
			$('#site-title a').text(to);
			
		} );
	} );
   
} )( jQuery );