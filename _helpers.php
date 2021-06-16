<?php

/**
 * Cut string to a set length to shorten task names
 *
 * @param string $string in full
 * @return string $string at maybe a shorter length
 */
function truncate( $string, $length = 30 ) {
	if ( strlen( $string ) > $length ) {
		$string    = strip_tags( $string );
		$first     = substr_replace( $string, '', ( floor( $length ) ), strlen( $string ) );
		$newstring = $first . '...';
		return ( strlen( $newstring ) > ( strlen( $string ) ) ) ? $string : $newstring;
	} else {
		return $string;
	}
}

function sanitize( $v = '', $preserve_emojis = false ) {
	if ( $preserve_emojis ) {
		return filter_var( htmlspecialchars( strip_tags($v) ), FILTER_SANITIZE_STRING );
	}
	return preg_replace( '/[^a-zA-Z0-9 _\-]/', '', $v );
}

function get_rest_response( $url, $token ) {
	if ( empty($url) )
		die('❌ invalid url in get_rest_response()');
	if ( empty($token) )
		die('❌ invalid token in get_rest_response()');

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt(
		$ch,
		CURLOPT_HTTPHEADER,
		array(
			'Authorization: Bearer ' . $token,
			'Content-type: application/json; charset=utf-8',
		)
	);
	$buffer = curl_exec( $ch );
	if ( $buffer === false )
		die( curl_error($ch) );
	curl_close( $ch );
	return json_decode($buffer);
}