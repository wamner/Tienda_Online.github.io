<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Document</title>

	<script src="https://www.paypal.com/sdk/js?client-id=AbalghECYa8q2MJ7-MMr6g7fSvz3xq02Vx4DTds9hnTAS9iiuR4gtgNTpDvMI-iX5El9oBk45q25j_9b"></script>

</head>
<body>
	<div id="paypal-button-container"></div>

	<script>
		paypal.Buttons({
			style:{
				color: 'blue',
				shape: 'pill',
				label: 'pay'
			},
			createOrder: function(data,actions){
				return actions.order.create({
					purchase_units: [{
						amount:{
							value: 100

						}
					}]

				});
			},
			onApprove: function(data, actions){
				actions.order.capture().then(function (detalles){
					window.location.href="completado.html"/*redirecciona a  otra pagina*/
					/*console.log(detalles);/*muesta los datos de la compra en la consola*/

				});

			},


			onCancel: function(data){
				alert("Pago cancelado")
				console.log(data);
			}


		}).render('#paypal-button-container');

	</script>

</body>
</html>