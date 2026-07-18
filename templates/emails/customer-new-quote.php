<?php
/**
 * Customer quote order email
 *
 * @author 	Aldaba Digital / Searle Creative
 * @package 	Woocommerce Quotation
 * @version     
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

?>

<?php $base_url = StaticAdqQuoteRequest::get_proposal_base_url( $order ); ?>

<?php do_action('adq_email_header', $email_heading); ?>

<p><?php _e( "This is a quote only. To place your order online you can click the Complete Purchase link below. If you prefer, you can fax your purchase order to us at (303) 779-2640 or scan and send it to info@estrelita.com. If you have any questions regarding your estimate or our products, please call (303) 779-2610 or email us. Thank you for your interest in Estrellita! We look forward to working with you.", 'woocommerce-quotation' ); ?></p>

<?php if ( $order->get_user() ) : ?>

    <p><?php printf( __( 'You can access your account area to view your orders here: %s.', 'woocommerce-quotation' ), '<a href="'.get_permalink( wc_get_page_id( 'myaccount' ) ).'">'.__( 'My Account', 'woocommerce-quotation' ).'</a>' ); ?></p>

<?php endif; ?>

<p><?php printf( __( 'If you would like to purchase these items you can %s this quote', 'woocommerce-quotation' ), 
        '<a href="'.$base_url.'&adq_action=accept">'.__( 'Accept', 'woocommerce-quotation' ).'</a>') ?></p>

<?php do_action( 'adq_email_proposal_after_header', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php echo __( 'Our proposal:', 'woocommerce-quotation' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce-quotation' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce-quotation' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce-quotation' ); ?></th>
		</tr>
	</thead>
	<tbody class="tb">
		<?php
		$items = $order->get_items();
		echo '<pre>';
		print_r($items);
		echo '</pre>';
		foreach ($items as $item) {
			$quoteTable .= '<tr>';
			$quoteTable .= '<td class="col">'.$item['name'].'</td>';
			$quoteTable .= '<td class="col">'.$item['qty'].'</td>';
			$quoteTable .= '<td class="col">$'.number_format($item['line_total'], 2,'.', ',').'</td>';
			$quoteTable .= '</tr>';		
		}
		?>
	</tbody>
	<tfoot>	
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'adq_email_footer' ); ?>