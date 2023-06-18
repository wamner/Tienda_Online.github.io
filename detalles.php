<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con =$db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] :'';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == ''){
echo 'Error al procesar la peticion';
exit;

} else{

  $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

  if ($token == $token_tmp) { 

$sql =$con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
$sql->execute([$id]);
if ($sql->fetchColumn() > 0){

   $sql =$con->prepare("SELECT nombre,descripcion,precio,descuento FROM productos WHERE id=? AND activo=1
    LIMIT 1");
   $sql->execute([$id]);
   $row = $sql->fetch(PDO::FETCH_ASSOC);
   $nombre =$row['nombre'];
   $descripcion =$row['descripcion'];
   $precio =$row['precio'];
   $descuento =$row['descuento'];
   $precio_desc = $precio - (($precio * $descuento) /100);
   $dir_images = 'images/productos/'. $id .'/';

   $rutaImg =$dir_images .'principal.jpeg';

   if(!file_exists($rutaImg)){
    $rutaImg = 'images/no-photo.jpeg';
   }

   $imagenes =array();
   if(file_exists($dir_images))
   {

   $dir =dir($dir_images);

   while(($archivo = $dir->read()) !=false){
    if($archivo != 'principal.jpeg' && (strpos($archivo, 'jpg')|| strpos($archivo, 'jpeg'))){
      $imagenes[] =$dir_images .$archivo;
     
    
  }
   }
   $dir->close();
   }
   $sqlCaracter =$con->prepare("SELECT DISTINCT(det.id_caracteristica) AS idCat, cat.caracteristica FROM
    det_prod_caracter AS det INNER JOIN caracteristicas AS cat ON det.id_caracteristica=cat.id   WHERE det.id_producto=?");
   $sqlCaracter->execute([$id]);

 

  } else{
    echo 'Error al procesar la peticion';
    exit;
   
  }
}else{
  echo 'Error al procesar la peticion';
  exit;

}

}

$sql =$con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tienda Online</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <link  href="css/estilos.css" rel="stylesheet"  >

</head>
<body>

  <form action="database.php" method="post">


	<header>
 
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand ">
       
        <strong>Tienda Online</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
      	
      	<ul class="navbar-nav me-auto mb-2 mb-lg-0">
      		<li class="nav-item">
      			<a href="#" class="nav-link active">Catalogo</a>
      			
      		</li>
      			<li class="nav-item">
      			<a href="#" class="nav-link">Contacto</a>
      			
      		</li>
      		
      	</ul>

      	<a href="checkout.php" class="btn btn-primary">
        Carrito <span id="num_cart" class ="badge bg-secondary"><?php echo $num_cart; ?><!-- aqui podemos agregar el mensaje de agregado al carrito --> </span> 
      </a>

      </div>

    </div>
  </div>
</header>
<main>
	<div class="container">

    <div class="row">
      <div class="col-md-6 order-md-1">

        <div id="carouselImages" class="carousel slide">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="<?php  echo $rutaImg; ?>" class="d-block w-100">


    </div>
       <?php foreach($imagenes as $img){ ?>
     <div class="carousel-item">

      <img src="<?php  echo $img; ?>" class="d-block w-100">
     
   
    </div>
       <?php } ?>
   
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="carouselImages" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

         
      </div>
        <div class="col-md-6 order-md-2">

          <h2> <?php echo $nombre; ?> </h2>

          <?php if ($descuento >0){ ?>
             <p><del> <?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
              <h2>
               <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
               <small class="text-success"><?php echo $descuento; ?> % descuento</small>

             </h2>
           <?php } else{ ?>

           }

          <h2> <?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>

        <?php }?>

          <p class="lead">
            <?php  echo $descripcion; ?>
          </p>

          <div class="col-3 my-3">

            <?php
            while($row_cat = $sqlCaracter->fetch(PDO::FETCH_ASSOC)){
              $idCat =$row_cat['idCat'];
              echo $row_cat['caracteristica']. ": ";

              echo "<select class='form-select' id='cat_$idCat'>";

              $sqlDet = $con->prepare("SELECT id, valor, stock FROM
              det_prod_caracter WHERE id_producto=? AND id_caracteristica=?");
              $sqlDet->execute([$id, $idCat]);
              while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)){
                echo "<option id='".$row_det['id']."'>".$row_det
                ['valor']. "</option>";
              }
              echo "</select>";
            }

            ?>
          </div>

          <div class="col-3 my-3">
            Cantidad: <input class="form-control" id="cantidad" name="cantidad" type="number"
            min="1" max="10" value="1">
            
          </div>

          <div class="d-grid gap-3 col-10 mx-auto">
            <button class="btn btn-primary" type="button">Comprar ahora</button>
            <button class="btn btn-outline-primary" id="btnAgregar" type="button"
            onclick = "addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')"
            >Agregar al carrito</button>
        
      </div>
      
    </div>
		    
</div>
</div>


</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <script >

    function addProducto(id, cantidad, token){

      let url='clases/carrito.php'
      let formData =new FormData()
      formData.append('id', id)
      formData.append('cantidad', cantidad)
      formData.append('token',token)

      fetch(url,{
        method: 'POST',
        body: formData,
        mode: 'cors'
      }).then(response => response.json())

      .then(data => {
        if(data.ok){
          let elemento = document.getElementById("num_cart")
          elemento.innerHTML = data.numero

        }
      })

    }
  </script>

</body>
</html>