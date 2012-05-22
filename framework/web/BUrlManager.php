<?php
class BUrlManager{
    
    public $caseSensitive=true;
    
    protected $_baseUrl = null;
    protected $_pathInfo = '';
    public function init(){
        
    }
    public function parseUrl($request){
        return $request->getRequestUri();
	}
    public function createUrl($route,$params=array(),$ampersand="&"){
        $route = trim($route,'/');
        $hostInfo = Bee::app()->getRequest()->getHostInfo();
        return $hostInfo.'/'.$route.$this->createPathInfo($params, "=", $ampersand);
    }
    /**
     * 根据参数数组生成一段链接
     * @param array $params 参数数组
     * @param string $equal 参数名称和参数值的链接字符
     * @param string $ampersand "参数名称-参数值"的连接字符
     * @param string $firstSymbol 第一个连接字符,默认是"?"
     * @return string 生成的链接
     */
    public function createPathInfo($params,$equal,$ampersand,$firstSymbol='?'){
        $pairs = array();
        if($c=count($params)>0){
            $firstParam = array_splice($params,0,1);
            $path = $firstSymbol.urlencode(key($firstParam)).$equal.urlencode(array_shift($firstParam));
            if($c>1){
                $path .= $ampersand;
                foreach($params as $k=>$v){
                    if(is_array($v)){
                        $pairs[] = $this->createPathInfo($v, $equal, $ampersand,$ampersand);
                    }else{
                        $pairs[] = urlencode($k).$equal.urldecode($v);
                    }
                }
            }
            return $path.implode($ampersand,$pairs);
        }else{
            return "";
        }
    }
}