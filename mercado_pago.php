<?php

require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken('TEST-927378699045220-121410-b696bb65dce656d4fbcba8565565d92e-500492562');

$preference= new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->id ='0001';
$item-> title ='Producto CDP';
$item->quantity = 1;
$item->unit_price = 150.00;

$preference->items = array($item);

$preference->back_urls = array(
	   /*Cuando lo ballamos a subir al hosting remplazamos la url por la del hosting*/
	"success" =>"http://localhost/pasarela/captura.php",
	"failure" =>"http://localhost/pasarela/fallo.php"

);
$preference->auto_return = "approved";
$preference->binary_mode = true;




$preference->save();



?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Documento</title>

	<script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>

	<h3>Mercado Pago</h3>
	<div class="checkout-btn"></div>

	<script> 
		const mp = new MercadoPago('TEST-e5ce11a1-0bfd-43c6-acc0-564341e2ae71',{
			
			
		});
		mp.checkout({
			preference:{
				id: '<?php echo $preference->id; ?>'
			},
			render:{
				container: '.checkout-btn',
				label: 'Pagar con MP'
			}
		})
		

	</script>

</body>
</html>