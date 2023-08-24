<?php 
// 1- khởi tạo kết nối
// + chuỗi kết nối datasource Name:không được chứa dấu cách
const DB_DSN="mysql:host=localhost;dbname=quanlysinhvien;port=3306";

// + username login vào DB
const DB_USERNAME='root';
// + password login vào DB
const DB_PASSWORD='';
// sử dụng try catch để bắt ngoại lệ khi kết nối PDO
try{
    $connection = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);


}catch(PDOException $e){
    die("Lỗi kết nối: ".$e->getMessage());
}