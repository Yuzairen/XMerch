<?php
include_once 'database.php';
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'products_crud.php';

$is_admin = ($_SESSION['level'] == 'Admin');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>XMERCH : Products</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>
  <div class="container-fluid">
    <?php if ($is_admin) { ?>

    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2>Create New Product</h2>
      </div>
    <form action="products.php" method="post" enctype="multipart/form-data" class="form-horizontal">  
      <div class="form-group">
        <label for="pid" class="col-sm-3 control-label">Product ID</label>
        <div class="col-sm-9">
            <input name="pid" type="text" class="form-control" id="pid" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_id']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input name="name" type="text" class="form-control" id="name" placeholder="Product Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label for="price" class="col-sm-3 control-label">Price</label>
        <div class="col-sm-9">
            <input name="price" type="text" class="form-control" id="price" placeholder="Product Price" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_price']; ?>" required> 
        </div>
    </div>

    <div class="form-group"> 
        <label for="manufacturer" class="col-sm-3 control-label">Manufacturer</label>
        <div class="col-sm-9">
            <select name="manufacturer" class="form-control" id="manufacturer" required>
              <option value="Hot Toys" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Hot Toys") echo "selected"; ?>>Hot Toys</option>
              <option value="Sideshow Collectibles" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Sideshow Collectibles") echo "selected"; ?>>Sideshow Collectibles</option>
              <option value="Iron Studios" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Iron Studios") echo "selected"; ?>>Iron Studios</option>
              <option value="PCS" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="PCS") echo "selected"; ?>>PCS</option>
              <option value="Alex Ross Art" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Alex Ross Art") echo "selected"; ?>>Alex Ross Art</option>
              <option value="Kotobukiya" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Kotobukiya") echo "selected"; ?>>Kotobukiya</option>
              <option value="Tamashii Nations" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Tamashii Nations") echo "selected"; ?>>Tamashii Nations</option>
              <option value="Mondo" <?php if(isset($_GET['edit'])) if($editrow['fld_product_manufacturer']=="Mondo") echo "selected"; ?>>Mondo</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="category" class="col-sm-3 control-label">Category</label>
        <div class="col-sm-9">
            <select name='category' class="form-control" id="category" required>
              <option value="Art Print" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Art Print") echo "selected"; ?>>Art Print</option>
              <option value="Premium Format Figure" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Premium Format Figure") echo "selected"; ?>>Premium Format Figure</option>
              <option value="Sixth Scale Figure" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Sixth Scale Figure") echo "selected"; ?>>Sixth Scale Figure</option>
              <option value="1:10 Scale Statue" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="1:10 Scale Statue") echo "selected"; ?>>1:10 Scale Statue</option>
              <option value="Statues" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Statues") echo "selected"; ?>>Statues</option>
              <option value="1:3 Scale Statue" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="1:3 Scale Statue") echo "selected"; ?>>1:3 Scale Statue</option>
              <option value="Maquette" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Maquette") echo "selected"; ?>>Maquette</option>
              <option value="Collectible Figure" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Collectible Figure") echo "selected"; ?>>Collectible Figure</option>
              <option value="Sixth Scale Diorama" <?php if(isset($_GET['edit'])) if($editrow['fld_product_category']=="Sixth Scale Diorama") echo "selected"; ?>>Sixth Scale Diorama</option>
            </select> 
        </div>
    </div>

    <div class="form-group">
        <label for="height" class="col-sm-3 control-label">Height</label>
        <div class="col-sm-9">
            <input name="height" type="text" class="form-control" id="height" placeholder="Product Height" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_height']; ?>" required> 
        </div>
    </div>

    <div class="form-group">
        <label for="weight" class="col-sm-3 control-label">Weight</label>
        <div class="col-sm-9">
            <input name="weight" type="text" class="form-control" id="weight" placeholder="Product Weight" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_weight']; ?>" required>
        </div>
    </div>

    <div class="form-group">
      <label for="fileToUpload" class="col-sm-3 control-label">Product Image (JPG/PNG)</label>
      <div class="col-sm-9">
        <?php if (isset($_GET['edit'])) { ?>
          <input type="hidden" name="current_image" value="<?php echo $editrow['fld_product_image']; ?>">
          <p class="current_image"><strong>Current Picture:</strong> <?php echo $editrow['fld_product_image']; ?></p>
        <?php } ?>
        <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" accept=".jpg,.jpeg,.png">
      </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if (isset($_GET['edit'])) { ?>
            <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_id']; ?>">
            <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
            <?php } else { ?>
            <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
            <?php } ?>
            <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
        </div>
    </div>
    </form>
  </div>
  </div>
  <?php } ?>

  <div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="page-header">
        <h2>Products List</h2>
      </div>
      <table class="table table-striped table-bordered">
      <tr>
        <th>Product ID</th>
        <th>Name</th>
        <td>Price</th>
        <th>Manufacturer</th>
        <th>Category</th>
        <th>Height</th>
        <th>Weight</th>
        <th>Actions</th>
    
      </tr>
      <?php
      // Read
      $per_page = 10;
      if (isset($_GET["page"]))
        $page = $_GET["page"];
      else
        $page = 1;
      $start_from = ($page-1) * $per_page;
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM tbl_products_a193602_pt2 LIMIT $start_from, $per_page");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }
      catch(PDOException $e){
            echo "Error: " . $e->getMessage();
      }
      foreach($result as $readrow) {
      ?>   
      <tr>
        <td><?php echo $readrow['fld_product_id']; ?></td>
        <td><?php echo $readrow['fld_product_name']; ?></td>
        <td><?php echo $readrow['fld_product_price']; ?></td>
        <td><?php echo $readrow['fld_product_manufacturer']; ?></td>
        <td><?php echo $readrow['fld_product_category']; ?></td>
        <td><?php echo $readrow['fld_product_height']; ?> cm</td>
        <td><?php echo $readrow['fld_product_weight']; ?> kg</td>
        <?php if ($is_admin) { ?>
        <td>
          <a href="products_details.php?pid=<?php echo $readrow['fld_product_id']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
          <a href="products.php?edit=<?php echo $readrow['fld_product_id']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
          <a href="products.php?delete=<?php echo $readrow['fld_product_id']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
        </td>

        <?php } elseif ($_SESSION['level'] == 'Normal Staff') { ?>
            <td>
                <a href="products_details.php?pid=<?php echo $readrow['fld_product_id']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
            </td>
        <?php } ?>
      </tr>
      <?php
      }
      $conn = null;
      ?>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <nav>
          <ul class="pagination">
          <?php
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM tbl_products_a193602_pt2");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $total_records = count($result);
          }
          catch(PDOException $e){
                echo "Error: " . $e->getMessage();
          }
          $total_pages = ceil($total_records / $per_page);
          ?>
          <?php if ($page==1) { ?>
            <li class="disabled"><span aria-hidden="true">«</span></li>
          <?php } else { ?>
            <li><a href="products.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
          <?php
          }
          for ($i=1; $i<=$total_pages; $i++)
            if ($i == $page)
              echo "<li class=\"active\"><a href=\"products.php?page=$i\">$i</a></li>";
            else
              echo "<li><a href=\"products.php?page=$i\">$i</a></li>";
          ?>
          <?php if ($page==$total_pages) { ?>
            <li class="disabled"><span aria-hidden="true">»</span></li>
          <?php } else { ?>
            <li><a href="products.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
          <?php } ?>
        </ul>
      </nav>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>