<?php
/**
 * Template Name: Product
 *
 * Description: A page template for displaying product pages & options
 *
 * @since 1.0.0
 */

function allOptionCombos($input) {
    $result = array();
    $prices = array();
    $final = array();

    while (list($key, $values) = each($input)) {

        if (empty($values)) {
            continue;
        }
        if (empty($result)) {
            foreach($values as $value) {
                $result[] = array($key => $value);
            }
        }
        else {

            $append = array();

            foreach($result as &$product) {

                $product[$key] = array_shift($values);
                $copy = $product;
                foreach($values as $item) {
                    $copy[$key] = $item;
                    $append[] = $copy;
                }
                array_unshift($values, $product[$key]);
            }
            $result = array_merge($result, $append);
        }
    }

    foreach($result as $key => $res) {

 		  foreach($res as $opt) {
	    	if(strpos($opt, ';') !== false) {
	    		$price = explode(";", $opt);
	    		$prices[$key][0] = $prices[$key][0]+$price[1];
	    	}
	    	else {
	    		$prices[$key][0] = $prices[$key][0]+0;
	    	}
	    }

    }

    $final['results'] = $result;
    $final['prices'] = $prices;

    return $final;
}

?>

<?php
while ( have_posts() ) : the_post();
?>
	  <article id="product-<?php the_ID(); ?>" <?php post_class("product-information"); ?>>
		<h2 class="product-title h1 bold">
			<?php the_title(); ?>
		</h2>

		<h2 class="product-price pull-left text-danger"><strong>$<?php echo str_replace("$", "", CFS()->get( 'price' )); ?></strong></h2>


		<h2 class="product-stock pull-left text-<?php echo (CFS()->get( 'in_stock' )) ? "success" : "warning" ?>"><span class="h4"><strong>
			<?php
			if(CFS()->get( 'in_stock' )) {
				echo '<i class="fa fa-check"></i>In Stock';
			}
			 else {
			 	echo '<i class="fa fa-times"></i>Out Of Stock';
			 }
			?>
		</strong></span></h2>

		<div class="clearfix"></div>

	    <div class="entry-content description clearfix">

			  	<div class="wp_cart_variation_section">
				  	<div class="panel panel-default">

						  <div class="panel-body">
					   		<?php
						    	$optionString = '';
						    	$optionsArray = array();
						    	$pricesArray = array();

						    	$optionsVariants = CFS()->get( 'product_options_variations' );
						    	$optionCount = 0;
						    	foreach ( $optionsVariants as $key => $option ) {
									$optionString .="var".($key+1)."='".$option['option_name']."|".$option['option_variants']."' ";
									//explode("|", $option['option_variants']);

									$variants = $option['option_variants'];
									//$images = $option['option_images'];
									$optionsArray[$option['option_name']]=array();
									array_push($optionsArray[$option['option_name']], "None");

									echo '<span class="wp_cart_variation_name option-name">'.$option['option_name'].' : </span><span class="label label-danger disclaimer">'.($option['option_disclaimer']!="" ? $option['option_disclaimer'] : "" ).'</span>
									<ul class="list-group product-list-group">';

									foreach($variants as $key => $variant) {

										array_push($optionsArray[$option['option_name']], $variant['option_variant_name'].";".$variant['option_variant_price']."");


										echo '<li class="list-group-item"><label>'.($variant['option_variant_image']!="" ? '<a class="option-image" href="'.wp_get_attachment_url( $variant['option_variant_image'] ).'">'.wp_get_attachment_image( $variant['option_variant_image'], "thumbnail" ).'</a>' : '').'<input class="checkbox" type="checkbox" name="'.$optionCount.'" value="'.$variant['option_variant_price'].'"><span class="text">'.$variant['option_variant_name'].' <strong>(+$'.$variant['option_variant_price'].')</strong></span></label></li>';
										$optionCount++;
									}
								 	echo '</ul>';
								}
								?>

                <div class="buttons-holder">
                		<?php
                			$buttons = allOptionCombos($optionsArray);


                			$title = get_the_title();
                			foreach($buttons['results'] as $key => $options) {
                				foreach($options as $option => $value) {
                					$opt .= "(".$option.": ".$value.") ";
                				}
                				$opt = str_replace(";", " +$", $opt);

                				echo print_wp_cart_button_for_product($title." ".$opt."", (str_replace("$", "", CFS()->get( 'price' ))+$buttons['prices'][$key][0]));

                				unset($opt);
                			}
                		?>
                </div>
            </div>
          </div>

	    </div><!-- .entry-content -->

	</article>

		<div id="tabs-container" class="col-md-12">
			<ul class="nav nav-tabs" role="tablist">
			  	<li role="presentation" class="active"><a href="#product-info" aria-controls="messages" role="tab" data-toggle="tab">Description</a></li>
					<li role="presentation"><a href="#product-reviews" aria-controls="settings" role="tab" data-toggle="tab">Customer Reviews</a></li>
			</ul>
			<div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="product-info">

					<section class="container product-info">
						<?php echo CFS()->get( 'full_description' ); ?>
					</section>

				</div>
				<div role="tabpanel" class="tab-pane fade in" id="product-reviews">

					<section class="container product-reviews">
						<?php echo "Reviews coming soon! We'd love to here from you, send us pictures of your Build-A-Luge kit in action and it might be featured here."; ?>
					</section>

				</div>
			</div>
		</div>
<?php
endwhile;
?>


<script>
jQuery(document).ready(function($) {
	$(".wp_cart_variation_section").on("change", "input[type='checkbox']", function(e) {
		var basePrice = parseFloat('<?php echo CFS()->get( 'price' ) ?>');
		var newPrice = 0;

		$(this).parents(".product-list-group").find(".checkbox").not($(this)).parents("label").removeClass("active");

		if(!$(this).parents("label").hasClass("active")) {
			$(this).parents("label").addClass("active");
		}
		else {
			$(this).parents("label").removeClass("active");
		}

		$(".wp_cart_variation_section .product-list-group").each(function(i) {
			if($(this).find(".active").length>0) {
				$optPrice = parseFloat($(this).find(".active input").val());
				newPrice=parseFloat(newPrice+$optPrice);
				console.log($(this).find(".active input").val());
			}
			else {
				//console.log("ERROR");
			}
		});

		var finalPrice = Number(parseFloat(basePrice+newPrice).toFixed(2));

		$(".wp_cart_button_wrapper").hide();
		$(".wp_cart_button_wrapper input[name='price'][value='"+finalPrice+"']").parents(".wp_cart_button_wrapper").show();

		$(".product-price strong").text("$"+parseFloat(basePrice+newPrice).toFixed(2));
	});
});
</script>
