<?php

session_start();

include_once("database-handler.php");

$db_handler = new Database();
$db_handler->checkSetup();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | SpaceTech</title>
    <link rel="stylesheet" a href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="products.css">
</head>
<body>

    <?php include_once("header.php"); ?>

<section id="hero"> 
    
    <h2>Products deals</h2>
    <p>List of Products</p>
</section>

<section id="products1" class = "section-p1">   
    
    <div class="products-container">  
        
        <?php

        $products_list = $db_handler->getAllProducts(true);

        foreach ($products_list as $product) {
            echo '
            
            <div class="pro" onclick="window.location.href=\'product.php?id='. htmlspecialchars($product["product_id"], ENT_QUOTES) . '\'"> 
          <img id="myimage" src="images/products/'. htmlspecialchars($product["product_id"], ENT_QUOTES) . '.jpg" alt="">
          <div class="description"> 
          <span>'. htmlspecialchars($product["product_name"], ENT_QUOTES) . '</span>
          <h5>'. htmlspecialchars($product["product_desc"], ENT_QUOTES) . '</h5>
          <h4> £'. htmlspecialchars($product["product_price"], ENT_QUOTES) . '</h4>
          </div>
        </div>  
            
            ';

        }


        ?>
    

    </div>
</section>

<section id ="pagination" class = "section-p1" >
<a href ="#">1</a>
<a href ="#">2</a>
<a href ="#">3</a>

</section>


<script src="products.js"></script>
<?php include_once("footer.php"); ?>
</body>
</html>
