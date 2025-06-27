<?php
include_once 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>XMERCH : Product Details</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<?php
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_GET['pid']) && !empty($_GET['pid'])) {
      $pid = htmlspecialchars($_GET['pid']);

      $stmt = $conn->prepare("SELECT * FROM tbl_products_a193602_pt2 WHERE fld_product_id = :pid");
      $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
      $stmt->execute();
      $readrow = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$readrow) {
          echo "<script>alert('Product not found.'); window.location.href='products.php';</script>";
          exit();
      }
  } else {
      echo "<script>alert('Invalid Product ID.'); window.location.href='products.php';</script>";
      exit();
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>

<div class="container-fluid">
  <div class="row">
    <!-- Product Image -->
    <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-2 well well-sm text-center">
      <?php
      $image_path = !empty($readrow['fld_product_image']) ? $readrow['fld_product_image'] : 'default_image.png';

      if (file_exists($image_path)) {
          echo '<img src="' . htmlspecialchars($image_path) . '" class="img-responsive" alt="Product Image">';
      } else {
          echo '<p>No image available</p>';
          echo '<img src="default_image.png" class="img-responsive" alt="Default Image">';
      }
      ?>
    </div>

    <!-- Product Details -->
    <div class="col-xs-12 col-sm-5 col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Product Details</strong></div>
        <div class="panel-body">
          Below are the specifications of the product.
        </div>
        <table class="table">
          <tr>
            <td class="col-xs-4 col-sm-4 col-md-4"><strong>Product ID</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_id']); ?></td>
          </tr>
          <tr>
            <td><strong>Name</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_name']); ?></td>
          </tr>
          <tr>
            <td><strong>Price</strong></td>
            <td>RM <?php echo htmlspecialchars(number_format($readrow['fld_product_price'], 2)); ?></td>
          </tr>
          <tr>
            <td><strong>Manufacturer</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_manufacturer']); ?></td>
          </tr>
          <tr>
            <td><strong>Category</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_category']); ?></td>
          </tr>
          <tr>
            <td><strong>Height</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_height']); ?> cm</td>
          </tr>
          <tr>
            <td><strong>Weight</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_weight']); ?> kg</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
