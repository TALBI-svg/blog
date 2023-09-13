<?php
// ATTR_ERRMODE 
// ERRMODE_EXCEPTION

$host='localhost';
$db='blog';
$user='root';
$password='';


try{
    $connection=new PDO('mysql:host='.$host.';dbname='.$db.'',$user,$password);
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo 'connection success';
}catch(PDOException $ex){
    echo "<h1 style='color:red;display:flex;flex-direction:row;justify-content:center;align-items:center;height:100vh;font-weight:bold;'>Failed connection to db!</h1>";
    exit;

}