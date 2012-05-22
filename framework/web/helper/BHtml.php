<?php
/**
 *
 * @author zouliming
 */
class BHtml {
    /**
     * 把html转换成html实体
     * @param String $text 需要被转换的数据
     * @return String 转换后的数据
     */
    public static function encode($text){
		return htmlspecialchars($text,ENT_QUOTES,Bee::app()->charset);
	}
    /**
     * 生成一个a标签
     * @param String $text 文本内容
     * @param mixed $url 如果为字符串,则表示url内容;如果为数组,则第一个参数是Controller/Action,第二个参数是url参数和值的对应关系;
     * @param Array $htmlOptions
     * @return type 
     */
    public static function link($text,$url='#',$htmlOptions=array()){
        if($url!==''){
			$htmlOptions['href']=self::normalizeUrl($url);
        }
		return self::tag('a',$htmlOptions,$text);
    }
    /**
     * 生成Html元素
	 * @param string $tag 标签名称
	 * @param array $htmlOptions 元素属性. 这个值将用{@link encode()}这个方法进行HTML编码
     * 如果设置的有'encode'这个属性,并且他的值是false,那么其他的属性值不会被HTML编码
     * 属性值为NUll的属性将不会被转换
	 * @param mixed $content 内容将被放在开始和结束标签中间.它不会被Html编码.
	 * 如果是false,代表没有body内容.
	 * @param boolean $closeTag 是否生成结束标签.
	 * @return string the 生成的Html标签元素
     */
    public static function tag($tag,$htmlOptions=array(),$content=false,$closeTag=true){
		$html='<' . $tag . self::renderAttributes($htmlOptions);
		if($content===false){
			return $closeTag ? $html.' />' : $html.'>';
        }else{
			return $closeTag ? $html.'>'.$content.'</'.$tag.'>' : $html.'>'.$content;
        }
	}
    /**
     * 生成正常Url
     * @param mixed $url 如果为字符串,则表示url内容;如果为数组,则第一个参数是Controller/Action,第二个参数是url参数和值的对应关系;
     * @return String 生成的Url字符串
     */
    public static function normalizeUrl($url){
		if(is_array($url)){
			if(isset($url[0])){
				if(($c=Bee::app()->getController())!==null){
					$url=$c->createUrl($url[0],array_splice($url,1));
                }else{
					$url=Bee::app()->createUrl($url[0],array_splice($url,1));
                }
			}else{
				$url='';
            }
		}
		return $url==='' ? Bee::app()->getRequest()->getUrl() : $url;
	}
    /**
	 * 赋予Html属性.
	 * @param array $htmlOptions 要被赋予的属性
	 * @return string the rendering result
	 */
	public static function renderAttributes($htmlOptions){
		static $specialAttributes=array(
			'checked'=>1,
			'declare'=>1,
			'defer'=>1,
			'disabled'=>1,
			'ismap'=>1,
			'multiple'=>1,
			'nohref'=>1,
			'noresize'=>1,
			'readonly'=>1,
			'selected'=>1,
		);

		if($htmlOptions===array()){
			return '';
        }

		$html='';
		if(isset($htmlOptions['encode'])){
			$raw=!$htmlOptions['encode'];
			unset($htmlOptions['encode']);
		}else{
			$raw=false;
        }

		if($raw){
			foreach($htmlOptions as $name=>$value){
				if(isset($specialAttributes[$name])){
					if($value){
						$html .= ' ' . $name . '="' . $name . '"';
                    }
				}else if($value!==null){
					$html .= ' ' . $name . '="' . $value . '"';
                }
			}
		}else{
			foreach($htmlOptions as $name=>$value){
				if(isset($specialAttributes[$name])){
					if($value){
						$html .= ' ' . $name . '="' . $name . '"';
                    }
				}else if($value!==null){
					$html .= ' ' . $name . '="' . self::encode($value) . '"';
                }
			}
		}
		return $html;
	}
}

?>
