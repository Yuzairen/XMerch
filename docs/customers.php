<?php
include_once 'database.php';
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'customers_crud.php';

$is_admin = ($_SESSION['level'] == 'Admin');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>XMERCH : Customers</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <?php if ($is_admin) { ?>
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Customer</h2>
        </div>
        <form action="customers.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="cid" class="col-sm-3 control-label">Customer ID</label>
            <div class="col-sm-9">
              <input name="cid" type="text" class="form-control" id="cid" placeholder="Customer ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_id']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="cname" class="col-sm-3 control-label">Customer Name</label>
            <div class="col-sm-9">
              <input name="name" type="text" class="form-control" id="cname" placeholder="Customer Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_name']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="phone" class="col-sm-3 control-label">Phone Number</label>
            <div class="col-sm-9">
              <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone Number" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_phone']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
              <input type="hidden" name="oldcid" value="<?php echo $editrow['fld_customer_id']; ?>">
              <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil"></span> Update</button>
              <?php } else { ?>
              <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus"></span> Create</button>
              <?php } ?>
              <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase"></span> Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php } ?>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="page-header">
          <h2>Customers List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Customer ID</th>
              <th>Customer Name</th>
              <th>Phone Number</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Read
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT * FROM tbl_customers_a193602_pt2");
              $stmt->execute();
              $result = $stmt->fetchAll();
            } catch(PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
            foreach($result as $readrow) {
            ?>
            <tr>
              <td><?php echo $readrow['fld_customer_id']; ?></td>
              <td><?php echo $readrow['fld_customer_name']; ?></td>
              <td><?php echo $readrow['fld_customer_phone']; ?></td>
              <?php if ($is_admin) { ?>
              <td>
                <a href="customers.php?edit=<?php echo $readrow['fld_customer_id']; ?>" class="btn btn-success btn-xs">Edit</a>
                <a href="customers.php?delete=<?php echo $readrow['fld_customer_id']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs">Delete</a>
              </td>

              <?php } elseif ($_SESSION['level'] == 'Normal Staff') { ?>
                  <td>
                    <label onclick="alert('You do not have permission for this action'); return false;" class="btn btn-success btn-xs">Edit</label>
                    <label onclick="alert('You do not have permission for this action'); return false;" class="btn btn-danger btn-xs">Delete</label>  
                  </td>
              <?php } ?>
            </tr>
            <?php } $conn = null; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
  