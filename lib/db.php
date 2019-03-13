<?php
header('Access-Control-Allow-Origin:*');
  require_once __DIR__.'/config.php';

//$pdo = new PDO("uri:mysqlpdo.ini","root","xNEOm7VvPC5VUrm9");//pdo文件形式
// $pdo = new PDO("mysql:host=".HOST.";dbname=".DBNAME.,"root","xNEOm7VvPC5VUrm9");//pdo文件形式
// $pdo->query('set names utf8');
// return $pdo;

try{
    //pdo连接mysql
    $pdo = new PDO("mysql:host=".HOST.";dbname=".DBNAME,"root","wf7AbzQwsW1iOYAp");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);//PDO::ERRMODE_WARNING==>1(异常捕获报警模式)
    $pdo->query('set names utf8');
    return $pdo;
 }catch(PDOException $e){
    //  die('数据库连接失败: '.$e->getMessage());
     return '数据库连接失败: '.$e->getMessage();
 }