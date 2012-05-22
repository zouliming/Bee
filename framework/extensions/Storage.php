<?php
class Storage extends BApplicationComponent{
    private $s;
    public function __consturct(){
        $this->s = new SaeStorage();
    }
    /**
     * 获取文件的内容
     *
     * @param string $domain 
     * @param string $filename 
     * @return string 成功时返回文件内容，否则返回false
     * @author Elmer Zhang
     */
    public function read( $domain, $filename ){
        $s->read( 'example' , 'thebook') ;
    }
    /**
     * 取得访问存储文件的url
     *
     * @param string $domain 
     * @param string $filename 
     * @return string 
     */
    public static function getUrl( $domain, $filename ) {
        $this->s->getUrl($domain,$filename);
    }
}
?>