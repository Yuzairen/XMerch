<?php
include_once 'database.php';
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['level'] == 'Normal Staff') {
    echo "
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css' rel='stylesheet'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>

    <!-- Access Denied Modal -->
    <div id='accessDeniedModal' class='modal fade' tabindex='-1' role='dialog'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header bg-danger'>
            <h4 class='modal-title text-danger'>Access Denied</h4>
          </div>
          <div class='modal-body'>
            <p>You don't have permission to enter this page. You will be redirected to the home page.</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-danger' id='redirectBtn'>OK</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function() {
        $('#accessDeniedModal').modal('show');

        $('#redirectBtn').click(function() {
          window.location.href = 'index.php';
        });
      });
    </script>
    ";
    exit;
}

include_once 'staffs_crud.php';

$is_admin = ($_SESSION['level'] == 'Admin');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>XMERCH : Staffs</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <div class="container">
    <div class="row">
      <?php if ($is_admin) { ?>
      <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Staff</h2>
        </div>
        <form action="staffs.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="sid" class="col-sm-3 control-label">Staff ID</label>
            <div class="col-sm-9">
              <input name="sid" type="text" class="form-control" id="sid" placeholder="Staff ID" value="<?php if (isset($_GET['edit'])) echo $editrow['fld_staff_id']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="sname" class="col-sm-3 control-label">Staff Name</label>
            <div class="col-sm-9">
              <input name="name" type="text" class="form-control" id="sname" placeholder="Staff Name" value="<?php if (isset($_GET['edit'])) echo $editrow['fld_staff_name']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-sm-3 control-label">Email Address</label>
            <div class="col-sm-9">
              <input name="email" type="text" class="form-control" id="email" placeholder="Email Address" value="<?php if (isset($_GET['edit'])) echo $editrow['fld_staff_email']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="password" class="col-sm-3 control-label">Password</label>
            <div class="col-sm-9">
              <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group">
            <label for="level" class="col-sm-3 control-label">User Level</label>
            <div class="col-sm-9">
              <select name="level" class="form-control" id="level" required>
                <option value="Admin">Admin</option>
                <option value="Normal Staff">Normal Staff</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
                <input type="hidden" name="oldsid" value="<?php echo $editrow['fld_staff_id']; ?>">
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
      <div class="col-md-8 col-md-offset-2">
        <div class="page-header">
          <h2>Staffs List</h2>
        </div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Staff ID</th>
              <th>Staff Name</th>
              <th>Email Address</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Read
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193602_pt2");
              $stmt->execute();
              $result = $stmt->fetchAll();
            } catch (PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
            foreach ($result as $readrow) {
            ?>
            <tr>
              <td><?php echo $readrow['fld_staff_id']; ?></td>
              <td><?php echo $readrow['fld_staff_name']; ?></td>
              <td><?php echo $readrow['fld_staff_email']; ?></td>
              <td>
                <a href="staffs.php?edit=<?php echo $readrow['fld_staff_id']; ?>" class="btn btn-success btn-xs">Edit</a>
                <a href="staffs.php?delete=<?php echo $readrow['fld_staff_id']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs">Delete</a>
              </td>
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
