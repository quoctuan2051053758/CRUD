<?php 
session_start();
require_once 'connection.php';
if(!isset($_GET['id'])||!is_numeric($_GET['id'])){
    $_SESSION['error']='Tham số id không hợp lệ';
    header('location: index.php');
    exit();
}
$id=$_GET['id'];
$sql_select_one="SELECT * FROM students WHERE id=:id";
$obj_select_one=$connection->prepare($sql_select_one);
$selects=[
    'id'=>$id
];

$obj_select_one->execute($selects);
$student=$obj_select_one->fetch(PDO::FETCH_ASSOC);

$error='';
if(isset($_POST['submit'])){
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
    if(empty($error)){
        $filename=$student['avatar'];
        if($avatar['error']==0){
            $dir_upload='uploads';
            if(!file_exists($dir_upload)){
                mkdir($dir_upload);
            }
            unlink("$dir_upload/$filename");
            $filename=time()."".$avatar['name'];
            $is_upload=move_uploaded_file($avatar['tmp_name'],"$dir_upload/$filename");
            var_dump($is_upload);
        }
        // insert vào csdl
        $sql_update="UPDATE students SET name=:name,age=:age,avatar=:filename,description=:description WHERE id=:id";
        // b2: thực thi: insert trả về
        $obj_update=$connection->prepare($sql_update);
        $updates=[
            ':name'=>$name,
            ':age'=>$age,
            ':filename'=>$filename,
            ':description'=>$description,
            ':id'=>$id
        ];
        $is_update=$obj_update->execute($updates);

        var_dump($is_update);
        if($is_update){
            $_SESSION['success']='sửa sinh viên thành công';
            header('location: index.php');
            exit();
        }
    }
}

?>
<h3 style="color: red;"><?php echo $error ?></h3>
<h1>Sửa sinh viên #<?php echo $student['id'] ?></h1>
<a href="index.php">Về trang danh sách</a>
<table cellpadding="8">
    <form action="" method="post" enctype="multipart/form-data" >
        <tr>
            <td>Họ tên</td>
            <td><input type="text" name="name" value="<?php echo $student['name'] ?>"></td>
        </tr>
        <tr>
            <td>Tuổi</td>
            <td><input type="text" name="age" value="<?php echo $student['age'] ?>"></td>
        </tr>
        <tr>
            <td>Ảnh đại diện</td>
            <td><input type="file" name="avatar" > <br>
            <img src="uploads/<?php echo $student['avatar'] ?>" alt="" height="80px">
        </td>
        </tr>
        <tr>
            <td>Mô tả sinh viên</td>
            <td><textarea name="description" id="" cols="20" rows="5"><?php echo $student['description'] ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Rave" style="background-color:green; color: white">
            <input type="reset" name="reset" value="Reset"></td>
            
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