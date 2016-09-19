

=== product-configurator ===
Contributors: shirso
Tags: product builder, customization, woocommerce
Requires at least: 4.4
Tested up to: 4.5
Stable tag: 1.0.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Customize Woocommerce products with HTML5 Canvas.

== Description ==
Product customization with WC attributes and terms.
Unlimited Custom Color and Texture.  
Price variation as WC variable product
Thick/Thin Cords position change as per combination
User can select Model and test customized product
All customized items and final product image can be viewed on WC order page
Unlimited product type

== Installation ==
By composer :
{
 \"repositories\" : [
		   {
               \"type\" : \"vcs\",
               \"url\" : \"git@github.com:shirso/product-configurator.git\"
			}
     ],
     \"extra\" : {
          \"installer-paths\" : {
               \"wp-content/plugins/{$name}/\" : [\"type:wordpress-plugin\"],
               \"wp-content/themes/{$name}/\" : [\"type:wordpress-theme\"]
          }
     },
    \"minimum-stability\": \"dev\",
    \"require\": {
		 \"shirso/product-configurator\" : \"1.0.*\"
    }
}

