
//////////////////////////////////////////////////
//ショートコードで囲んだ範囲のp,&nbspタグを消す
//////////////////////////////////////////////////
function noFunc( $atts, $html = null ) {
	$html = str_replace( '<p>' , '' , $html );
	$html = str_replace( '</p>' , '' , $html );
	$html = str_replace( '&nbsp' , '' , $html );
	return $html;
}
add_shortcode('no', 'noFunc');
