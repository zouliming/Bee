<?php
class BHttpRequest{
    private $_requestUri;
    private $_hostInfo;
    public function init(){
        
    }
    public function getRequestUri(){
        if(isset($_SERVER['REQUEST_URI'])){
            $this->_requestUri = $_SERVER['REQUEST_URI'];
            return $this->_requestUri;
        }else{
            throw new BException("BHttpRequest获取不到 'REQUEST_URI'");
        }
    }
    /**
	 * Returns part of the request URL that is after the question mark.
	 * @return string part of the request URL that is after the question mark
	 */
	public function getQueryString()
	{
		return isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
	}
    /**
	 * Returns the request type, such as GET, POST, HEAD, PUT, DELETE.
	 * @return string request type, such as GET, POST, HEAD, PUT, DELETE.
	 */
	public function getRequestType()
	{
		return strtoupper(isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'GET');
	}
    /**
	 * Returns the URL referrer, null if not present
	 * @return string URL referrer, null if not present
	 */
	public function getUrlReferrer()
	{
		return isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null;
	}
    public function redirect($url,$terminate=true,$statusCode=302)
	{
		if(strpos($url,'/')===0)
			$url=$this->getHostInfo().$url;
		header('Location: '.$url, true, $statusCode);
		if($terminate)
			Yii::app()->end();
	}
    /**
	 * 判断请求是否是安全请求 (https).
	 * @return boolean 返回是否是安全请求 (https)
	 */
    public function getIsSecureConnection()
	{
		return isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'],'on');
	}
    /**
     * 返回网站的域名Url
     * @return String 
     */
    public function getHostInfo(){
        if($this->_hostInfo===null){
            $http = "http";
            if($this->getIsSecureConnection()){
                $http = "https";
            }
            if(isset($_SERVER['HTTP_HOST'])){
				$this->_hostInfo=$http.'://'.$_SERVER['HTTP_HOST'];
            }else{
				$this->_hostInfo=$http.'://'.$_SERVER['SERVER_NAME'];
			}
        }
        return $this->_hostInfo;
    }
}
?>
