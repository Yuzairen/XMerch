<?php
include_once 'database.php';
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'orders_crud.php';

$is_admin = ($_SESSION['level'] == 'Admin');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>XMERCH : Orders</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Order</h2>
        </div>
        <form action="orders.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="oid" class="col-sm-3 control-label">Order ID</label>
            <div class="col-sm-9">
              <input name="oid" type="text" class="form-control" id="oid" placeholder="Order ID" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_num']; ?>">
            </div>
          </div>
            <div class="form-group">
              <label for="orderdate" class="col-sm-3 control-label">Order Date</label>
              <div class="col-sm-9">
                <input name="orderdate" type="text" class="form-control" id="orderdate" placeholder="Order Date" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_date']; ?>"> 
              </div>
            </div>
            <div class="form-group">
              <label for="sid" class="col-sm-3 control-label">Staff</label>
              <div class="col-sm-9">
                <select name="sid" class="form-control" id="sid">
                  <?php
                  try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193602_pt2");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                  }
                  catch(PDOException $e){
                        echo "Error: " . $e->getMessage();
                  }
                  foreach($result as $staffrow) {
                  ?>
                    <?php if((isset($_GET['edit'])) && ($editrow['fld_staff_id']==$staffrow['fld_staff_id'])) { ?>
                      <option value="<?php echo $staffrow['fld_staff_id']; ?>" selected><?php echo $staffrow['fld_staff_name'];?></option>
                    <?php } else { ?>
                      <option value="<?php echo $staffrow['fld_staff_id']; ?>"><?php echo $staffrow['fld_staff_name'];?></option>
                    <?php } ?>
                  <?php
                  } // while
                  $conn = null;
                  ?> 
                </select> 
              </div>
            </div>
            <div class="form-group">
              <label for="cid" class="col-sm-3 control-label">Customer</label>
              <div class="col-sm-9">
                <select name="cid" class="form-control" id="cid">
                  <?php
                  try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $stmt = $conn->prepare("SELECT * FROM tbl_customers_a193602_pt2");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                  }
                  catch(PDOException $e){
                        echo "Error: " . $e->getMessage();
                  }
                  foreach($result as $custrow) {
                  ?>
                    <?php if((isset($_GET['edit'])) && ($editrow['fld_customer_id']==$custrow['fld_customer_id'])) { ?>
                      <option value="<?php echo $custrow['fld_customer_id']; ?>" selected><?php echo $custrow['fld_customer_name']?></option>
                    <?php } else { ?>
                      <option value="<?php echo $custrow['fld_customer_id']; ?>"><?php echo $custrow['fld_customer_name']?></option>
                    <?php } ?>
                  <?php
                  } // while
                  $conn = null;
                  ?> 
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
                <?php if (isset($_GET['edit'])) { ?>
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

      <div class="row">
        <div class="col-xs-12">
        <div class="page-header">
          <h2>Order List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Staff ID</th>
            <th>Customer ID</th>
            <th></th>
          </tr>
          <?php
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM tbl_orders_a193602, tbl_staffs_a193602_pt2, tbl_customers_a193602_pt2 WHERE ";
            $sql = $sql."tbl_orders_a193602.fld_staff_num = tbl_staffs_a193602_pt2.fld_staff_id and ";
            $sql = $sql."tbl_orders_a193602.fld_customer_num = tbl_customers_a193602_pt2.fld_customer_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
          }
          catch(PDOException $e){
                echo "Error: " . $e->getMessage();
          }
          foreach($result as $orderrow) {
          ?>
          <tr>
            <td><?php echo $orderrow['fld_order_num']; ?></td>
            <td><?php echo $orderrow['fld_order_date']; ?></td>
            <td><?php echo $orderrow['fld_staff_name'] ?></td>
            <td><?php echo $orderrow['fld_customer_name'] ?></td>
            <?php if ($is_admin) { ?>
            <td>
              <a href="orders_details.php?oid=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-info btn-xs" role="button">Details</a>
              <a href="orders.php?edit=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
              <a href="orders.php?delete=<?php echo $orderrow['fld_order_num']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
            </td>
            <?php } elseif ($_SESSION['level'] == 'Normal Staff') { ?>
                <td>
                    <a href="orders_details.php?oid=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-info btn-xs" role="button">Details</a>
                </td>
            <?php } ?>
          </tr>
          <?php } $conn = null; ?>
        </table>
      </div>
    </div>
  </div>

  <!-- jQuery and Bootstrap JavaScript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>