<?php 

include("_auth.php");

$search_data=htmlspecialchars(mysqli_real_escape_string($conn, $_POST['search_text']));
$output = '';

if(!empty($search_data))
{
    $output .='
    <thead class="thead-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Image</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>';
    $sql="select sub_category.sub_category_id as sub_category_id, sub_category.name as sub_category_name, sub_category.category_id as category_id, sub_category.sub_category_image as sub_category_image, category.name as category_name from sub_category join category on sub_category.category_id=category.id where (sub_category.name like '%{$search_data}%' or category.name like '%{$search_data}%') and (sub_category.is_deleted=0 and category.is_deleted=0)";
    $result=mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0)
    {
        $i=1;
        while($row=mysqli_fetch_assoc($result))
        {
            $output .='
                    <tr>
                        <td>
                            '.$i++.'
                        </td>
                        <td>
                            <span>'.$row['category_name'].'</span>
                        </td>
                        <td>
                            <span>'.ucwords(substr($row['sub_category_name'], 0, 30)).'';
                        
                        if(strlen($row['sub_category_name'])>30)
                        {
                            $output .='...';
                        }
            $output .='
                    </span>
                    </td>
                    
                    <td>
                        <img src="../../images/sub-category/'.$row['sub_category_image'].'" class="w-40px rounded m-r-10">
                    </td>
                    
                    <td class="text-right">
                        <button type="button" class="btn btn-danger btn-sm delete-sub-category-btn" data-sub-category-id="'.$row['sub_category_id'].'">
                            <i class="fa-solid fa-trash-can px-2"></i>
                        </button>
                        <button type="button" class="btn btn-success btn-sm update-sub-category-btn" data-sub-category-id="'.$row['sub_category_id'].'" data-toggle="modal" data-target="#ModalCenter">
                            <i class="fa-solid fa-pen-to-square px-2"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm view-sub-category-btn" data-sub-category-id="'.$row['sub_category_id'].'" data-toggle="modal" data-target="#ModalCenter">
                            <i class="fa-regular fa-eye px-2"></i>
                        </button>
                    </td>
                </tr>';
        }
    }

$output .='
                            </tbody>';
}
else
{
    $output .='
        <thead class="thead-primary">
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Image</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>';
    $sql="select sub_category.sub_category_id as sub_category_id, sub_category.name as sub_category_name, sub_category.category_id as category_id, sub_category.sub_category_image as sub_category_image, category.name as category_name from sub_category join category on sub_category.category_id=category.id where sub_category.is_deleted=0 and category.is_deleted=0";
    $result=mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0)
    {
        $i=1;
        while($row=mysqli_fetch_assoc($result))
        {
            $output .='
                    <tr>
                        <td>
                            '.$i++.'
                        </td>
                        <td>
                            <span>'.$row['category_name'].'</span>
                        </td>
                        <td>
                            <span>'.ucwords(substr($row['sub_category_name'], 0, 30)).'';
                        
                        if(strlen($row['sub_category_name'])>30)
                        {
                            $output .='...';
                        }
            $output .='
                    </span>
                    </td>
                    
                    <td>
                        <img src="../../images/sub-category/'.$row['sub_category_image'].'" class="w-40px rounded m-r-10">
                    </td>
                    
                    <td class="text-right">
                        <button type="button" class="btn btn-danger btn-sm delete-sub-category-btn" data-sub-category-id="'.$row['sub_category_id'].'">
                            <i class="fa-solid fa-trash-can px-2"></i>
                        </button>
                        <button type="button" class="btn btn-success btn-sm update-sub-category-btn" data-sub-category-id="'.$row['sub_category_id'].'" data-toggle="modal" data-target="#ModalCenter">
                            <i class="fa-solid fa-pen-to-square px-2"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm view-sub-category-btn" data-sub-category-id="'.$row['sub_category_id'].'" data-toggle="modal" data-target="#ModalCenter">
                            <i class="fa-regular fa-eye px-2"></i>
                        </button>
                    </td>
                </tr>';
        }
    }

$output .='
        </tbody>';
}

echo $output;

?>