<?php 
session_start();
require_once 'connection.php';
if(!isset($_GET['id'])||!is_numeric($_GET['id'])){
    $_SESSION['error']='Tham số id không hợp lệ';
    header('location: index.php');
    exit();
}
$id=$_GET['id'];
$sql_delete="DELETE FROM students WHERE id =:id";
$obj_delete=$connection->prepare($sql_delete);
$deletes=[
    ':id'=>$id
];
$is_delete=$obj_delete->execute($deletes);
if($is_delete){
    $_SESSION['success']='xóa thành công';
}
else{
    $_SESSION['error']="xóa sp thất bại";
}
header('location: index.php');
exit();

?>