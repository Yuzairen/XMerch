<?php
session_start(); // Start the session
include_once 'database.php'; // Include the database connection
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>XMERCH : Login</title>
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('products/concreate.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      font-family: 'Arial', sans-serif;
    }
    .container {
      margin-top: 10%;
      max-width: 500px;
      background: rgba(0, 0, 0, 0.75);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    h1, h4 {
      margin-bottom: 20px;
      font-weight: 600;
      color: #eaeaea;
      text-align: center;
    }
    .form-control {
      background: rgba(255, 255, 255, 0.9);
      color: #333;
      border: none;
      border-radius: 5px;
    }
    .btn-primary {
      background: #007bff;
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      font-size: 16px;
      font-weight: 600;
      transition: background 0.3s;
    }
    .btn-primary:hover {
      background: #0056b3;
    }
    .alert {
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome to XMERCH</h1>
    <h4>Please login</h4>
    <form action="" method="post" class="form-horizontal">
      <div class="form-group">
        <label for="staff_id" class="col-sm-3 control-label">Staff ID:</label>
        <div class="col-sm-9">
          <input type="text" name="staff_id" id="staff_id" class="form-control" placeholder="Enter Staff ID" required>
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-3 control-label">Password:</label>
        <div class="col-sm-9">
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
        </div>
      </div>
    </form>

    <?php
    if (isset($_POST['login'])) {
        $staff_id = $_POST['staff_id'];
        $password = $_POST['password'];

        try {
            // Query the database for the given staff ID
            $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193602_pt2 WHERE fld_staff_id = :staff_id");
            $stmt->bindParam(':staff_id', $staff_id);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validate staff ID and password
            if ($user && $password === $user['fld_staff_password']) { // Compare plain text password
                $_SESSION['user_id'] = $user['fld_staff_id'];
                $_SESSION['level'] = $user['fld_staff_level'];
                header("Location: index.php");
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Invalid Staff ID or password.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
