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
    public function _construct(User $_user){
        $this->_user=$_user;
    }
    /**
     * api接口的启动方法
     */
    public function Run(){
        try{
            $this->setMethod();
            $this->setResource();

            if($this->_requestResource=='users'){

            }

            
        }catch(Exception $e){
            $this->_json($e->getMessage(),$e->getCode());
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
        
        header("Content-Type: application/json; charset=utf-8");
        $result = ['message'=>$message,'code'=>$code];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);//JSON_UNESCAPED_UNICODE中文不转码
        die();
    }




}