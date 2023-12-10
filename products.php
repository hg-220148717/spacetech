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
    
        
         
         <div class="pro"> 
            <img id="myimage" src="img/products img/ACER Predator Orion 7000  Gaming PC - Intel® Core™ i9, RTX 4080, 2 TB HDD & 1 TB SSD 2749.jpg" alt="">
            <div class="description"> 
            <span>  Acer Predator  </span>
            <h5> Intel® Core™ i9, RTX 4080, 1 TB SSD </h5>
            <h4> £2749 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/PCSPECIALIST Icon 220 Gaming PC - AMD Ryzen 5, RTX 3050, 1 TB SSD 799.jpg" alt="">
            <div class="description"> 
            <span>  PC specialist  </span>
            <h5> AMD Ryzen 5, RTX 3050, 1 TB SSD  </h5>
            <h4> £799 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/2023 65” QN95C  Smart TV 2199.jpg" alt="">
            <div class="description"> 
            <span>  Samsung  </span>
            <h5> 65” QN95C  Smart TV </h5>
            <h4> £2199 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/JVC  Roku TV 40 Smart 179.jpg" alt="">
            <div class="description"> 
            <span>  JVC  </span>
            <h5> Roku TV 40 Smart </h5>
            <h4> £179 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/sony bravia 50 499.jpg" alt="">
            <div class="description"> 
            <span>  Sony Bravia </span>
            <h5> Bravia Smart TV 50 </h5>
            <h4> £499 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/apple watch se 259.jpg" alt="">
            <div class="description"> 
            <span>  Apple  </span>
            <h5> Watch SE  </h5>
            <h4> £259 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/fitbit inspire 2 44.jpg" alt="">
            <div class="description"> 
            <span>  FitBit   </span>
            <h5> Inspire 2 </h5>
            <h4> £44 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/samsung watch 6 163.jpg" alt="">
            <div class="description"> 
            <span>  Samsung  </span>
            <h5> Watch 6 </h5>
            <h4> £163 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/iphone 15 titanium 256 1199.jpg" alt="">
            <div class="description"> 
            <span>  Apple  </span>
            <h5> iPhone 15 256 titanium </h5>
            <h4> £1199 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/pixel fold porcelain 1749.jpg" alt="">
            <div class="description"> 
            <span>  Google Pixel  </span>
            <h5> Fold Porecelain </h5>
            <h4> £1749 </h4>
            </div>
           </div>

           <div class="pro"> 
            <img id="myimage" src="img/products img/samsung z fold 5 256 1750.jpg" alt="">
            <div class="description"> 
            <span>  Samsung  </span>
            <h5> Z Fold 5 256 </h5>
            <h4> £1750 </h4>
            </div>
           </div>

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
