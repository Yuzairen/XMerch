<?php
include_once 'database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getLoggedInUserName($conn, $staff_id) {
    if (!$conn) {
        throw new Exception("Database connection not initialized.");
    }

    try {
        $stmt = $conn->prepare("SELECT fld_staff_name FROM tbl_staffs_a193602_pt2 WHERE fld_staff_id = :staff_id");
        $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['fld_staff_name'] : "Unknown User";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Ensure $conn is initialized
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$current_user_name = isset($_SESSION['user_id']) ? getLoggedInUserName($conn, $_SESSION['user_id']) : "Guest";

?>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">XMERCH</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><span class="navbar-text">Welcome, <?php echo htmlspecialchars($current_user_name); ?></span></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="products.php">Products</a></li>
            <li><a href="customers.php">Customers</a></li>
            <li><a href="staffs.php">Staffs</a></li>
            <li><a href="orders.php">Orders</a></li>
          </ul>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Log Out</a></li>
        <?php else: ?>
          <li><a href="login.php">Log In</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
