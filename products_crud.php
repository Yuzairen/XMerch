<?php
include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create
    if (isset($_POST['create'])) {
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $manufacturer = $_POST['manufacturer'];
        $category = $_POST['category'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];

        $stmt = $conn->prepare("INSERT INTO tbl_products_a193602_pt2 
            (fld_product_id, fld_product_name, fld_product_price, fld_product_manufacturer, fld_product_category, fld_product_height, fld_product_weight, fld_product_image) 
            VALUES (:pid, :name, :price, :manufacturer, :category, :height, :weight, :image)");

        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':manufacturer', $manufacturer, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);

        $image = handleImageUpload($pid);

        if ($image !== false) {
            $stmt->execute();
            echo "<script>alert('Product successfully added!'); window.location.href='products.php';</script>";
        }
    }

   // Update
if (isset($_POST['update'])) {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $manufacturer = $_POST['manufacturer'];
    $category = $_POST['category'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $oldpid = $_POST['oldpid'];

    // Get the current image from POST (hidden input in form)
    $current_image = $_POST['current_image'];

    // Handle image upload (either keep the current image or upload a new one)
    $image = handleImageUpload($pid, $current_image);

    // Prevent update if image upload failed
    if ($image === false) {
        echo "<script>window.history.back();</script>";
        exit;
    }

    try {
        // Prepare SQL UPDATE statement
        $stmt = $conn->prepare("UPDATE tbl_products_a193602_pt2 SET 
            fld_product_id = :pid, 
            fld_product_name = :name, 
            fld_product_price = :price, 
            fld_product_manufacturer = :manufacturer, 
            fld_product_category = :category, 
            fld_product_height = :height, 
            fld_product_weight = :weight, 
            fld_product_image = :image 
            WHERE fld_product_id = :oldpid");

        // Bind parameters
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':manufacturer', $manufacturer, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);

        $stmt->execute();
        echo "<script>alert('Product successfully updated!'); window.location.href='products.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


    // Delete
    if (isset($_GET['delete'])) {
        $pid = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM tbl_products_a193602_pt2 WHERE fld_product_id = :pid");
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->execute();

        // Delete both JPG and PNG images
        foreach (['jpg', 'png'] as $ext) {
            $image_path = "products/" . strtolower($pid) . "." . $ext;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        echo "<script>alert('Product deleted successfully!'); window.location.href='products.php';</script>";
    }

    // Edit
    if (isset($_GET['edit'])) {
        $pid = $_GET['edit'];

        $stmt = $conn->prepare("SELECT * FROM tbl_products_a193602_pt2 WHERE fld_product_id = :pid");
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->execute();

        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;

function handleImageUpload($pid, $current_image = null) {
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "products/";
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $pid . "." . $imageFileType;

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not an image.');</script>";
            return false;
        } elseif ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "<script>alert('File size exceeds 500KB.');</script>";
            return false;
        } elseif (!in_array($imageFileType, ['jpg', 'png'])) {
            echo "<script>alert('Only JPG and PNG files are allowed.');</script>";
            return false;
        } elseif ($check[0] > 300 || $check[1] > 400) {
            echo "<script>alert('Image dimensions should not exceed 300x400 pixels.');</script>";
            return false;
        } else {
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                return $target_file;
            } else {
                echo "<script>alert('Error uploading image.');</script>";
                return false;
            }
        }
    }
    return $current_image;
}

?>
