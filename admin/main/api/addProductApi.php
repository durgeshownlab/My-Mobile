<?php

include("_auth.php");

if(!isset($_POST['product_name']) || empty($_POST['product_name']))
{
    echo 0;
    exit;
}
else if(!isset($_POST['product_base_price']) || empty($_POST['product_base_price']) || !is_numeric($_POST['product_base_price']) || $_POST['product_base_price']<0)
{
    echo 0;
    exit;
}
else if(!isset($_POST['product_price']) || empty($_POST['product_price']) || !is_numeric($_POST['product_price']) || $_POST['product_price']<0)
{
    echo 0;
    exit;
}
else if($_POST['product_price']>$_POST['product_base_price'])
{
    echo 0;
    exit;
}
else if(!isset($_POST['product_quantity']) || empty($_POST['product_quantity']) || !is_numeric($_POST['product_quantity']) || $_POST['product_quantity']<0)
{
    echo 0;
    exit;
}
else if(!isset($_POST['product_category']) || empty($_POST['product_category']))
{
    echo 0;
    exit;
}
else if(!isset($_POST['product_sub_category']) || empty($_POST['product_sub_category']))
{
    echo 0;
    exit;
}
else if(!isset($_POST['product_desc']) || empty($_POST['product_desc']))
{
    echo 0;
    exit;
}


//file handling 
$product_main_image = $_FILES['product_main_image'];

$file_name = $product_main_image['name'];
$file_tmp_name = $product_main_image['tmp_name'];
$file_error = $product_main_image['error'];

if ($file_error === UPLOAD_ERR_OK) 
{
    // Validate file type and size
    $allowed_extensions = array(
        'jpg',
        'jpeg',
        'png',
        'gif',
        'bmp',
        'tiff',
        'tif',
        'webp',
        'svg',
        'ico',
        'psd',
        'eps',
        'ai'
    );
    $max_file_size = 5 * 1024 * 1024; // 5MB

    $file_info = pathinfo($file_name);
    $file_extension = strtolower($file_info['extension']);

    if (!in_array($file_extension, $allowed_extensions)) {
        echo 'Invalid file format';
        exit;
    }

    if ($product_main_image['size'] > $max_file_size) {
        echo 'File size exceeds the maximum limit of 5MB.';
        exit;
    }

    // Generate a unique filename
    $new_file_name = uniqid('', true) . '.' . $file_extension;

    // Specify the directory to which the file should be moved
    $upload_directory = '../../../images/products/';

    // Move the file to the upload directory
    $destination = $upload_directory . $new_file_name;
    if (move_uploaded_file($file_tmp_name, $destination)) 
    {
        
    } 
    else 
    {
        echo 'Failed to move the uploaded file.';
        exit;
    }
} 
else 
{
    echo 'File upload failed';
    exit;
}


$product_name=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['product_name']));
$product_price=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['product_price']));
$product_desc=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['product_desc']));
$product_sub_category=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['product_sub_category']));
$product_base_price=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['product_base_price']));
$product_quantity=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['product_quantity']));

$sql="insert into products (product_name, product_image, product_price, product_desc, sub_category_id, base_price, quantity) values ('{$product_name}', '{$new_file_name}', {$product_price}, '{$product_desc}', {$product_sub_category}, {$product_base_price}, {$product_quantity})";

$result=mysqli_query($conn, $sql);


if($result)
{
    $product_id=mysqli_insert_id($conn);
    $status=true;

    $specification_names = explode(',', $_POST['specification_names']);
    $specification_values = explode(',', $_POST['specification_values']);

    // print_r(count($specification_names));
    // exit;

    for($i=0; $i<count($specification_names); $i++)
    {

        $product_specification_name=htmlspecialchars(mysqli_real_escape_string($conn, $specification_names[$i]));
        $product_specification_value=htmlspecialchars(mysqli_real_escape_string($conn, $specification_values[$i]));

        $sql1 = "INSERT INTO specifications (product_id, name, value) VALUES ({$product_id} ,'{$product_specification_name}', '{$product_specification_value}');";

        $result1=mysqli_query($conn, $sql1);
        if(!$result1)
        {
            $status=false;
            break;
        }
    }

    if($status)
    {
        $upload_status=true;
        if (isset($_FILES["product_other_image"]) && is_array($_FILES["product_other_image"])) {
    
            // Loop through the uploaded files
            for($i = 0; $i < count($_FILES["product_other_image"]["name"]); $i++) {

                $file_name = $_FILES["product_other_image"]["name"][$i];
                $file_tmp_name = $_FILES["product_other_image"]["tmp_name"][$i];
                $file_size = $_FILES["product_other_image"]["size"][$i];
                $file_error = $_FILES["product_other_image"]["error"][$i];
    
                if ($file_error === UPLOAD_ERR_OK) 
                {
                    // Validate file type and size
                    $allowed_extensions = array(
                        'jpg',
                        'jpeg',
                        'png',
                        'gif',
                        'bmp',
                        'tiff',
                        'tif',
                        'webp',
                        'svg',
                        'ico',
                        'psd',
                        'eps',
                        'ai'
                    );
                    $max_file_size = 5 * 1024 * 1024; // 5MB

                    $file_info = pathinfo($file_name);
                    $file_extension = strtolower($file_info['extension']);

                    if (!in_array($file_extension, $allowed_extensions)) {
                        echo 'Invalid file format';
                        exit;
                    }

                    if ($file_size > $max_file_size) {
                        echo 'File size exceeds the maximum limit of 5MB.';
                        exit;
                    }

                    // Generate a unique filename
                    $new_file_name = uniqid('', true) . '.' . $file_extension;

                    // Specify the directory to which the file should be moved
                    $upload_directory = '../../../images/products/';

                    // Move the file to the upload directory
                    $destination = $upload_directory . $new_file_name;
                    if (move_uploaded_file($file_tmp_name, $destination)) 
                    {
                        $sql="insert into product_images (product_id, image_path) values ({$product_id}, '{$new_file_name}')";

                        $result=mysqli_query($conn, $sql);

                        if(!$result)
                        {
                            $upload_status=false;
                            break;
                        }
                    } 
                    else 
                    {
                        echo 'Failed to move the uploaded file.';
                        exit;
                    }
                } 
                else 
                {
                    echo 'File upload failed';
                    exit;
                }
            }
        }

        if($upload_status)
        {
            echo 1;
            exit;
        }
        else
        {
            echo 0;
            exit;
        }

        echo 1;
    }
    else
    {
        echo 0;
    }
}
else
{
    if (file_exists($destination)) {
        if (unlink($destination)) {
            // File deletion successful
            // echo 'File deleted successfully.';
        } else {
            // File deletion failed
            // echo 'Failed to delete the file.';
        }
    } else {
        // File does not exist
        // echo 'File not found.';
    }
    echo 0;
}

?>