<?php


class Rest{
    /**
     * 用户实例
     */
    private $_user;
    /**
     * 请求方法
     */
    private $_requestMethod;
     /**
     * 允许请求资源
     */
    private $_allowMethod=['GET','POST','PUT','DELETE'];

    /**
     * 请求资源
     */
    private $_requestResource;

    /**
     * 允许请求资源
     */
    private $_allowResource=['users'];
    /**
     * 常见状态码
     */
    private $_statusCode=[
        200=>'OK',
        204=>'NO Content',
        400=>'Bad Request',
        403=>'Forbidden',
        404=>'Not Found',
        405=>'Mehod Not Allow',
        500=>'Server Interbal Error'
    ];
    /**
     * 资源标识
     */
    private $_requestUri;
    public function __construct(User $_user){
        $this->_user=$_user;
       
    }
    /**
     * api接口的启动方法
     */
    public function Run(){
       // var_dump($this->_user);
        try{
            $this->setMethod();
            $this->setResource();

            if($this->_requestResource=='users'){
                //var_dump( 323232);
                
                $this->sebdUsers();
            }

            
        }catch(Exception $e){
            $this->_json($e->getMessage(),$e->getCode());
        }
        

    }
    /**
     * 处理用户逻辑
     */
    private function sebdUsers(){
        
        if($this->_requestMethod != 'POST'){
            throw new Exception('请求方法不被允许', 405);
        }
        if(empty($this->_requestUri)){
            throw new Exception('请求参数缺失', 400);
        }
        
        switch($this->_requestUri){
            case 'login':
            $this->dologin();
            break;
            case 'register':
            $this->doregister();
            break;
            default:
            throw new Exception('请求方法不被允许', 405);

        }

       
    }
    /**
     * 用户注册接口
     */
    private function doregister(){
        $data = $this->getBody();
        if(empty($data['username'])){
            throw new Exception('用户名不能为空', 400);
        }
        if(empty($data['password'])){
            throw new Exception('用户名密码不能为空', 400);
        }
       $user = $this->_user->register($data['username'],$data['password']);
       if($user){
            $this->_json('注册成功',200);
       }
    }
    /**
     * 获取请求参数
     */
    private function getBody(){
        $data = file_get_contents('php://input');
        if(empty($data)){
            throw new Exception('请求参数错误', 400);
        }
        return json_decode($data,true);
    }
    /**
     * 用户登陆
     */
    private function dologin(){
        $data = $this->getBody();
        if(empty($data['username'])){
            throw new Exception('用户名不能为空', 400);
        }
        if(empty($data['password'])){
            throw new Exception('用户名密码不能为空', 400);
        }
       $user = $this->_user->login($data['username'],$data['password']);
       if($user){
           session_start();
          $data = [
              'data'=>[
                  'user_id'=>$user['id'],
                  'username'=>$user['username'],
                  'token'=>session_id(),
              ],
              'message'=>'登录成功',
              'code'=>200
            ];
             
             echo json_encode($data,JSON_UNESCAPED_UNICODE);
       }
    }
    /**
     * 判断用户是否登入
     */
    private function isLogin($token){
        $sessionID = session_id();
        if($sessionId != $token){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 设置api的请求方法
     */
    private function setMethod(){
        
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        if(!in_array($this->_requestMethod,$this->_allowMethod)){
            throw new Exception('请求方法不被允许', 405);
        }
        
    }

     /**
     * 设置api的资源设置
     */
    private function setResource(){
       $path = $_SERVER['PATH_INFO'];
       $param = explode('/',$path);//拆分 根据/拆分
         //var_dump($param);
        $this->_requestResource = $param['1'];//路由在数字第二个
        if(!in_array($this->_requestResource,$this->_allowResource)){
            throw new Exception('请求资源不被允许', 405);
        }
        
        $this->_requestUri=$param['2'];//接口名
    }

    /**
     *返回json格式
      *$message  string 提示信息
      *code  int  提示编码
     */
    private function _json($message,$code){
        //var_dump($data);
        //header('HTTP/1.1',$code.' '.$this->_statusCode[$code]);
        header("Content-Type: application/json; charset=utf-8");
       
        if(!empty($message)){
            $result = ['message'=>$message,'code'=>$code];
           
            echo json_encode($result,JSON_UNESCAPED_UNICODE);//JSON_UNESCAPED_UNICODE中文不转码
        }
        
      
        die();
    }




}