<?php
	function get_product_availability($product) {
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}	
		$availability = $class = "";

		if ( $product->managing_stock() ) {
			if ( $product->is_in_stock() ) {

				if ( $product->get_total_stock() > 0 ) {

					$format_option = get_option( 'woocommerce_stock_format' );

					switch ( $format_option ) {
						case 'no_amount' :
							$format = __( 'Còn Hàng', 'wpdance' );
						break;
						case 'low_amount' :
							$low_amount = get_option( 'woocommerce_notify_low_stock_amount' );

							$format = ( $product->get_total_stock() <= $low_amount ) ? __( 'Only %s left in stock', 'wpdance' ) : __( 'In stock', 'wpdance' );
						break;
						default :
							$format = __( '%s Còn Hàng', 'wpdance' );
						break;
					}

					$availability = sprintf( $format, $product->stock );
					$class = 'in-stock';

					if ( $product->backorders_allowed() && $product->backorders_require_notification() )
						$availability .= ' ' . __( '(backorders allowed)', 'wpdance' );

				} else {

					if ( $product->backorders_allowed() ) {
						if ( $product->backorders_require_notification() ) {
							$availability = __( 'Available on backorder', 'wpdance' );
							$class        = 'available-on-backorder';
						} else {
							$availability = __( 'Còn Hàng', 'wpdance' );
						}
					} else {
						$availability = __( 'Hết Hàng', 'wpdance' );
						$class        = 'out-of-stock';
					}

				}

			} elseif ( $product->backorders_allowed() ) {
				$availability = __( 'Available on backorder', 'wpdance' );
				$class        = 'available-on-backorder';
			} else {
				$availability = __( 'Hết Hàng', 'wpdance' );
				$class        = 'out-of-stock';
			}
		} elseif ( ! $product->is_in_stock() ) {
			$availability = __( 'Hết Hàng', 'wpdance' );
			$class        = 'out-of-stock';
		} elseif ( $product->is_in_stock() ){
			$availability = __( 'Còn Hàng', 'wpdance' );
			$class        = 'in-stock';		
		}

		return apply_filters( 'woocommerce_get_availability', array( 'availability' => $availability, 'class' => $class ), $product );
	}
	
?>