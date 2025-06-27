<?php
session_start(); // Mulakan sesi

// Semak jika kakitangan sudah log masuk. Jika tidak, alihkan ke laman log masuk.
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header("Location: login.php"); // Alihkan ke laman log masuk
    exit(); // Hentikan skrip
}

// Semak jika pengguna disahkan dengan memeriksa 'user_id' sesi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'product_crud.php';

// Semak tahap pengguna dan tetapkan kebenaran
$is_admin = ($_SESSION['user_level'] == 'Admin');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TP-Link Mart Ordering System: Products</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <?php if ($is_admin) { ?>
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Product</h2>
        </div>
       <form action="products.php" method="post" class="form-horizontal" enctype="multipart/form-data" id="productForm">

          <div class="form-group">
            <label for="productid" class="col-sm-3 control-label">Product ID</label>
            <div class="col-sm-9">
              <input name="pid" type="text" class="form-control" id="productid" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_num']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productname" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <input name="name" type="text" class="form-control" id="productname" placeholder="Product Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productprice" class="col-sm-3 control-label">Price</label>
            <div class="col-sm-9">
              <input name="price" type="number" class="form-control" id="productprice" placeholder="Product Price" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_price']; ?>" min="0.0" step="0.01" required>
            </div>
          </div>
          <div class="form-group">
            <label for="producttype" class="col-sm-3 control-label">Type</label>
            <div class="col-sm-9">
              <select name="type" class="form-control" id="producttype" required>
                <option value="">Please select</option>
                <option value="Router" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Router") echo "selected"; ?>>Router</option>
                <option value="Smart Sensors" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Smart Sensors") echo "selected"; ?>>Smart Sensors</option>
                <option value="WiFi Camera" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "WiFi Camera") echo "selected"; ?>>WiFi Camera</option>
                <option value="Switch Features" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Switch Features") echo "selected"; ?>>Switch Features</option>
                <option value="Smart Hub" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Smart Hub") echo "selected"; ?>>Smart Hub</option>
                <option value="Smart Plugs" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Smart Plugs") echo "selected"; ?>>Smart Plugs</option>
                <option value="Smart Lighting" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Smart Lighting") echo "selected"; ?>>Smart Lighting</option>
                <option value="Smart Robot Vacuums" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Smart Robot Vacuums") echo "selected"; ?>>Smart Robot Vacuums</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="productquantity" class="col-sm-3 control-label">Quantity</label>
            <div class="col-sm-9">
              <input name="quantity" type="number" class="form-control" id="productquantity" placeholder="Quantity" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_quantity']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productwarranty" class="col-sm-3 control-label">Warranty Length (Years)</label>
            <div class="col-sm-9">
              <select name="warranty" class="form-control" id="productwarranty" required>
                <option value="1" <?php if(isset($_GET['edit']) && $editrow['fld_product_warranty'] == "1") echo "selected"; ?>>1 Year</option>
                <option value="2" <?php if(isset($_GET['edit']) && $editrow['fld_product_warranty'] == "2") echo "selected"; ?>>2 Years</option>
                <option value="3" <?php if(isset($_GET['edit']) && $editrow['fld_product_warranty'] == "3") echo "selected"; ?>>3 Years</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="productdimension" class="col-sm-3 control-label">Dimension</label>
            <div class="col-sm-9">
              <input name="dimension" type="text" class="form-control" id="productdimension" placeholder="Product Dimension" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_dimension']; ?>" required>
            </div>
          </div>
           <div class="form-group">
            <label for="productimage" class="col-sm-3 control-label">Product Image</label>
            <div class="col-sm-9">
              <?php if (isset($_GET['edit']) && !empty($editrow['fld_product_image'])) { ?>
                <p>Current image: <?php echo $editrow['fld_product_image']; ?></p>
              <?php } ?>
              <input name="image" type="file" class="form-control" id="productimage" <?php if (!isset($_GET['edit'])) echo 'required'; ?>>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
                <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_num']; ?>">
                <input type="hidden" name="oldimage" value="<?php echo $editrow['fld_product_image']; ?>">


                <button type="submit" name="update" class="btn btn-primary">Update</button>
              <?php } else { ?>
                <button type="submit" name="create" class="btn btn-primary">Create</button>
              <?php } ?>
              <button type="button" class="btn btn-default" onclick="resetForm()">Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php } ?>

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="page-header">
          <h2>Product List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Name</th>
              <th>Price</th>
              <th>Type</th>
              <th>Quantity</th>
              <th>Warranty</th>
              <th>Dimension</th>
              <?php if ($is_admin) { ?>
              <th>Actions</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            // Fetch and display products
            $per_page = 5;
            $page = isset($_GET["page"]) ? $_GET["page"] : 1;
            $start_from = ($page - 1) * $per_page;

            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT * FROM tbl_products_a195161_pt2 LIMIT $start_from, $per_page");
              $stmt->execute();
              $result = $stmt->fetchAll();
            } catch(PDOException $e) {
              echo "Error: " . $e->getMessage();
            }

            foreach($result as $readrow) {
            ?>
            <tr>
              <td><?php echo $readrow['fld_product_num']; ?></td>
              <td><?php echo $readrow['fld_product_name']; ?></td>
              <td><?php echo $readrow['fld_product_price']; ?></td>
              <td><?php echo $readrow['fld_product_type']; ?></td>
              <td><?php echo $readrow['fld_product_quantity']; ?></td>
              <td><?php echo $readrow['fld_product_warranty']; ?></td>
              <td><?php echo $readrow['fld_product_dimension']; ?></td>
              <td><img src="products/<?php echo $readrow['fld_product_image']; ?>" alt="Product Image" style="width: 100px; height: auto;"></td>
              <?php if ($is_admin) { ?>
              <td>
                <a href="products_details.php?pid=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
                <a href="products.php?edit=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
                <a href="products.php?delete=<?php echo $readrow['fld_product_num']; ?>" onclick="return confirm('Are you sure you want to delete this?');" class="btn btn-danger btn-xs" role="button">Delete</a>
              </td>
             <?php } elseif ($_SESSION['user_level'] == 'Normal Staff') { ?>
              <td>
             <a href="products_details.php?pid=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
             </td>
             <?php } ?>
            </tr>
            <?php } $conn = null; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <?php
            // Pagination logic
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_products_a195161_pt2");
              $stmt->execute();
              $total_records = $stmt->fetchColumn();
            } catch(PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
            $total_pages = ceil($total_records / $per_page);
            ?>

            <?php if ($page == 1) { ?>
              <li class="disabled"><a href="#">&laquo;</a></li>
            <?php } else { ?>
              <li><a href="products.php?page=<?php echo $page - 1; ?>" aria-label="Previous">&laquo;</a></li>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
              <?php if ($i == $page) { ?>
                <li class="active"><a href="#"><?php echo $i; ?></a></li>
              <?php } else { ?>
                <li><a href="products.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
              <?php } ?>
            <?php } ?>

            <?php if ($page == $total_pages) { ?>
              <li class="disabled"><a href="#">&raquo;</a></li>
            <?php } else { ?>
              <li><a href="products.php?page=<?php echo $page + 1; ?>" aria-label="Next">&raquo;</a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script>
  function resetForm() {
    document.getElementById('productForm').reset();
    var selects = document.querySelectorAll('#productForm select');
    selects.forEach(function(select) {
        select.selectedIndex = 0;
    });
    document.getElementById('productid').value = ''; 
    document.getElementById('productname').value = ''; 
    document.getElementById('productprice').value = '';
    document.getElementById('producttype').value = '';
    document.getElementById('productquantity').value = '';
    document.getElementById('productwarranty').value = ''; 
    document.getElementById('productdimension').value = ''; 
  }
  </script>

</body>
</html>