# WP-Simple-Paypal-Shopping-Cart-Product-Variatons

WP Simple PayPal Shopping Cart is a great, free, and easy solution. However it does not handle product variations out of the box and I found no plugins to extend to this functionality. Therefore I whipped this bit of code up fast. Certainly not a plug and go solution but this chunk of code and stuff should get you started. To view a demo of this working visit http://build-a-luge.com/ice-luge-kits/deluxe-build-a-luge-ice-luge-kit/

This has been testing on the latest version of Wordpress, CFS, and WP Simple PayPal Shopping Cart.

### Requirements

  - Custom Field Suite Plugin (https://wordpress.org/plugins/custom-field-suite/)
  - WP Simple PayPal Shopping Cart (https://wordpress.org/plugins/wordpress-simple-paypal-shopping-cart/)

### How it works

WP Simple PayPal Shopping Cart generates buttons to add products to the cart on page load and there is no way to dynamically update price or other options. We use a cartesian array to loop thru all possible product variations and create a button for each variant, and hide the and display the proper button when an options are set. The price will update in real time above the options. The button will also update but the user will be none the wiser as it appears as though nothing happens to the button.  

### Usage

Once you have both plugins installed and have configured WP Simple PayPal Shopping Cart, open CFS tools and import the code that is found in cfs_product_information_import.txt

From there take the code found in template product and incorporate it into a template of your choosing. That template file is not ready to be used out of the box and only contains the important parts to get this working, as your theme style and classes will most likely vary. There will be options in CFS for product images but the code to parse them is not in the template file, that will be on you to get working. Once you have the template file setup correctly be sure to set 'Product Information' to look at that template otherwise this will not work.

Finally take the CSS that is included in this repo and incorporate it into your site. If you don't do this all buttons will appear at once.
