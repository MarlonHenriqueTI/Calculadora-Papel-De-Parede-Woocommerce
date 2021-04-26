<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<style>
	p#indicacao {
		margin: 0;
		margin-bottom: -25px;
		color: #737373;
		font-size: 15px;
	}
	
	p#calcule-texto {
		font-size: 18px;
		margin: 2% 0;
		font-weight: 600;
		color: #4c4c4c;
	}
	div#resposta p {
		font-size: 1.3em;
		margin-bottom: -30px;
	}
	.popupaoc-button {
		padding: 10% 10%;
		text-align: center;
		background: #e91e63!important;
		border-radius: 5px;
		color: #fff;
		display: inline-block;
		text-decoration: none !important;
		-webkit-transition-duration: 0.4s;
		transition-duration: 0.4s;
	}
	
	.pp-aparecer {
		display: none;
	}
	
	.pp-aparecer-td {
		display: none;
	}
	
</style>
<script>
function buscarPalavras(palavra) {
    var palavram = palavra.toLowerCase();
    var word = palavram,
        queue = [document.getElementsByClassName('woocommerce-breadcrumb')[0]],
        curr;
    while (curr = queue.pop()) {
        palavraencontrada = curr.textContent.toLowerCase();
        if (!palavraencontrada.match(word)) continue;
        for (var i = 0; i < curr.childNodes.length; ++i) {
            switch (curr.childNodes[i].nodeType) {
                case Node.TEXT_NODE: // 3
                    if (curr.childNodes[i].textContent.toLowerCase().match(word)) {
                        console.log(curr); 
                        let aparecer = document.getElementsByClassName('pp-aparecer');
						let aparecertd = document.getElementsByClassName('pp-aparecer-td');
						let i = 0;
						let j =0;
						while(i<aparecer.length){
							aparecer[i].style.display = "table-row";
							i++;
						}
						while(j<aparecertd.length){
							aparecertd[j].style.display = "table-cell";
							j++;
						}
						
                    }
                    break;
                case Node.ELEMENT_NODE: // 1
                    queue.push(curr.childNodes[i]);
                    break;
            }
        }
    }
}	
	
function atualizar(){
	let valor = document.getElementById('pa_altura-da-parede');
	let valorDigitado = document.getElementById('valor-digitado');
	let quantidade = document.querySelectorAll('[title="Qtd"]');
	let resultado;
	let resposta = document.getElementById('resposta');

	if(valorDigitado.value <= 0.52){
		resultado = 1;
		quantidade[0].min = resultado;
        quantidade[0].value = resultado;
	} else {
		resultado = Math.ceil(valorDigitado.value/0.512);
		quantidade[0].min = resultado;
        quantidade[0].value = resultado;
	}
	if(resultado <= 1){
		resposta.innerHTML = "<hr><p><b>Você precisa de "+resultado+" rolinho de </b>"+parseInt(valor.value)/100+"m x 0,52m</p><br><small style='line-height: 15px;display: table;margin: 10px 0;'>Caso as medidas informadas na calculadora estejam corretas, não altere a quantidade informada</small><hr>";
	} else {
		resposta.innerHTML = "<hr><p><b>Você precisa de "+resultado+" rolinhos de </b>"+parseInt(valor.value)/100+"m x 0,52m</p><br><small style='line-height: 15px;display: table;margin: 10px 0;'>Caso as medidas informadas na calculadora estejam corretas, não altere a quantidade informada</small><hr>";
	}

	
}
	
function attQtd(){
	let valorDigitado = document.getElementById('valor-digitado');
	let quantidade = document.querySelectorAll('[title="Qtd"]');
	if(valorDigitado.value <= 0.52){
		resultado = 1;
		quantidade[0].min = resultado;
        quantidade[0].value = resultado;
	} else {
		resultado = Math.ceil(valorDigitado.value/0.512);
		quantidade[0].min = resultado;
        quantidade[0].value = resultado;
	}
	if(valorDigitado.value < 0){
		alert('O valor minimo é 0');
		valorDigitado.value = 0;
	}
}
	
document.addEventListener("DOMContentLoaded", function(event) {
    buscarPalavras('papeis de parede');
	
});
</script>
<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<tr style="background: #ffffff;" class="pp-aparecer">
					<td style="padding: 1em;background: #ffffff;" colspan="2">
						<p id="calcule-texto">
							🧮 Calcule a quantidade necessária
						</p>
					</td>
					<td style="padding: 1em;background: #ffffff;">
						<?php echo do_shortcode('[popup_anything id="2217"]'); ?>
					</td>
				</tr>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr style="background: #F7F7F7;"  class="pp-aparecer"><td style="padding: 1em;" colspan="3"><p id="indicacao">
						<b>Indicamos informar as medidas com sobras de 10 cm</b>
						</p></td></tr>
					<tr style="background: #F7F7F7;">
						<td style="padding: 1em;">
							<div class="form-control">
								<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label>
								<?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
									)
								);
								echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
							</div>
							</td>
						<td style="padding: 1em;" class="pp-aparecer-td">
							<div class="form-group">
								<label><b>Largura</b> da parede</label>
								<input id="valor-digitado" type="number" step="0.01" placeholder="0.00" name="largura" oninput="attQtd()" onkeyup="attQtd()" min="0">
							</div>
						</td>
						<td style="vertical-align: middle;" class="pp-aparecer-td"><button type="button" onclick="atualizar()" class="btn btn-primary">
							Calcular
							</button></td>
					</tr>
					<tr style="background: #F7F7F7;" class="pp-aparecer">
						<td style="padding: 1em;" colspan="3">
							<div id="resposta" style="text-align: center;"></div>
						</td>
					</tr>
				<?php endforeach; ?>
				
			</tbody>
		</table>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
