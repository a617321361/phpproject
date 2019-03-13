<?php


require_once __DIR__.'/Error.php';

class  User{



    private $_db;//定义一个数据库连接对象

    public function __construct(PDO $_db){
        $this->_db=$_db;
    }
    /**
     * 用户注册
     * $username  用户名
     * $password  密码
     * $time      创建时间
     */
    public function register($username,$password){
        if(empty($username)){
            throw new Exception('用户名不能为空', Error_code::USERNAME_CANNOT_NULL);
        }

        if(empty($password)){
            throw new Exception('用户密码不能为空', Error_code::PASSWORD_CANNOT_NULL);
        }
        if($this->isUsernameExit($username)){
            throw new Exception('用户名已存在', Error_code::USERNAME_ALREADY_EXIT);
        }
        $time = date('Y-m-d H:i:s',time());//获取当前时间
        $password = $this->_md5($password);
        $sql = "INSERT INTO `user` (`username`, `password`, `creat_time`) VALUES ('$username', '$password','$time')";
        
        $sm = $this->_db->prepare($sql);
        
        if(!$sm->execute()){//判断会否成功执行
 
            throw new Exception('注册失败', Error_code::REGISTER_FAIL);
        }

        //返回值
        return [
            'username'=>$username,
            'id'=>$this->_db->lastInsertId(),
            'creat_time'=>$time
        ];
        

    }

    /**
     * 用户登录
    * $username  用户名
     * $password  密码 
     */
    public function login($username,$password){
        if(empty($username)){
            throw new Exception('用户名不能为空', Error_code::USERNAME_CANNOT_NULL);
        }

        if(empty($password)){
            throw new Exception('用户密码不能为空', Error_code::PASSWORD_CANNOT_NULL);
        }
        $password = $this->_md5($password);
       $sql = "SELECT * FROM `user` WHERE `username` = '$username' AND `password` = '$password'";

       $sm = $this->_db->prepare($sql);

       if(!$sm->execute()){
        throw new Exception('登录失败', Error_code::LOGIN_FAIL);
       }
    //    var_dump($sm->execute());
       $re = $sm->fetch(PDO::FETCH_ASSOC);
       if(!$re){
        throw new Exception('用户名或密码错误', Error_code::USERNAME_OR_PASSWORD_FAIL);
       }

       return $re;


    }   





    /**
     * md5延值加密
     */
    private function _md5($password){
        return md5($password.SALT);
    }

    /**
     * 判断用户名是否已经存在
     */
    private function isUsernameExit($username){

        $sql = "SELECT * FROM user WHERE username = '$username'";//查询语句
        //预处理
        $sm = $this->_db->prepare($sql);
        //执行
        $sm->execute();
        $re= $sm->fetch(PDO::FETCH_ASSOC);

        return !empty($re);


    }





}