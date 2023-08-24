
<?php 

require_once 'connection.php';
session_start();
// xử lý form
// b1: debug
// echo '<pre>';
// print_r($_POST);
// print_r($_FILES);
// echo '</pre>';
// b2: 
$error='';
if(isset($_POST['submit'])){
    // b4:
    $name=$_POST['name'];
    $age=$_POST['age'];
    $description=$_POST['description'];
    $avatar=$_FILES['avatar'];
    if(empty($name)||empty($age)){
        $error='tên và giá phải nhập';
    }elseif(!is_numeric($age)){
        $error='giá phải nhập số';
    }elseif ($avatar['error']==0){
        // file upload phải là ảnh
        $ext=pathinfo($avatar['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $allows=['jpg','png','jpeg','gif'];
        if(!in_array($ext,$allows)){
            $error='file upload không phải là ảnh';
        }
        // file upload dung lượng không vượt quá 2mb
        $size_b=$avatar['size'];
        $size_mb=$size_b/1024/1024;
        if($size_mb>2){
            $error='file upload dung lượng không vượt quá 2mb';
        }
    }
    // b6:xử lý logic chính khi không có lỗi
    if(empty($error)){
        // upload file để lấy ra tên file
        $filename='';
        if($avatar['error']==0){
            $dir_upload='uploads';
            if(!file_exists($dir_upload)){
                mkdir($dir_upload);
            }
            $filename=time()."".$avatar['name'];
            $is_upload=move_uploaded_file($avatar['tmp_name'],"$dir_upload/$filename");
            var_dump($is_upload);
        }
        // insert vào csdl
        $sql_insert="INSERT INTO students(name, age, avatar,description) VALUES (:name,:age,:filename,:description)";
        // b2: thực thi: insert trả về
        $obj_insert=$connection->prepare($sql_insert);
        $inserts=[
            ':name'=>$name,
            ':age'=>$age,
            ':filename'=>$filename,
            ':description'=>$description
        ];
        $is_insert=$obj_insert->execute($inserts);

        var_dump($is_insert);
        if($is_insert){
            $_SESSION['success']='thêm sinh viên mới thành công';
            header('location: index.php');
            exit();
        }
        $error ='thêm mới thất bại';
    }
}


?>
<h3 style="color: red;"><?php echo $error; ?></h3>
<h1>Thêm sinh viên mới</h1>
<a href="index.php">Về trang danh sách</a>
    
<table cellpadding="8">
    <form action="" method="post" enctype="multipart/form-data">
        <tr>
            <td>Họ tên</td>
            <td><input type="text" name="name" value=""></td>
        </tr>
        <tr>
            <td>Tuổi</td>
            <td><input type="text" name="age"></td>
        </tr>
        <tr>
            <td>Ảnh đại diện</td>
            <td><input type="file" name="avatar" ></td>
        </tr>
        <tr>
            <td>Mô tả sinh viên</td>
            <td><textarea name="description" id="" cols="20" rows="5"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Save" style="background-color:green; color: white">
            <input type="reset" name="reset"  value="Reset"></td>
        </tr>
    </form>
    <style>
        input[type="submit"],input[type="reset"]{
            width: 25%;
            height: 35px;
            border-radius: 5px;
            border: none;
            transition: transform 0.4s ease;
        }
        input[type="reset"]:hover{
            transform: translateY(-4px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        input[type="submit"]:hover{
            transform: translateY(-4px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
    </style>
</table>