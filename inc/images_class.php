<?php
class Image {
    var $imageResource = NULL;
    var $target = NULL;
    var $enableTypes = array();
    var $imageInfo = array();
    var $createFunc = '';
    var $imageType = NULL;
   
    /**
     * Construct for this class
     *
     * @param string $image
     * @return Image
     */
    function Image($image = NULL) {
        //get enables
        if(imagetypes() & IMG_GIF) {
            $this->enableTypes[] = 'image/gif';
        }
        if(imagetypes() & IMG_JPEG) {
            $this->enableTypes[] = 'image/jpeg';
        }
        if (imagetypes() & IMG_JPG) {
            $this->enableTypes[] = 'image/jpg';
        }
        if(imagetypes() & IMG_PNG) {
            $this->enableTypes[] = 'image/png';
        }
        //end get
       
        if($image != NULL) {
            $this->setImage($image);
        }
    }
   
    /**
     * set a image resource
     *
     * @param string $image
     * @return boolean
     */
    function setImage($image) {
        if(file_exists($image) && is_file($image)) {
            $this->imageInfo = getimagesize($image);
            $img_mime = strtolower($this->imageInfo['mime']);
            if(!in_array($img_mime, $this->enableTypes)) {
               $this->_log('系统不能操作这种图片类型.');
			   return false;
            }
            switch ($img_mime) {
                case 'image/gif':
                    $link = imagecreatefromgif($image);
                    $this->createFunc = 'imagegif';
                    $this->imageType = 'gif';
                    break;
                case 'image/jpeg':
                case 'image/jpg':
                    $link = imagecreatefromjpeg($image);
                    $this->createFunc = 'imagejpeg';
                    $this->imageType = 'jpeg';
                    break;
                case 'image/png':
                    $link = imagecreatefrompng($image);
                    $this->createFunc = 'imagepng';
                    $this->imageType = 'png';
                    break;
                default:
                    $link = 'unknow';
                    $this->imageType = 'unknow';
                    break;
            }
            if($link !== 'unknow') {
                $this->imageResource = $link;
            } else {
                $this->_log('这种图片类型不能改变尺寸.');
				return false;
            }
            unset($link);
            return true;
        } else {
            return false;
        }
    }
   
    /**
     * set header
     *
     */
    function setHeader() {
        switch ($this->imageType) {
            case 'gif':
                header('content-type:image/gif');
                break;
            case 'jpeg':
                header('content-type:image/jpeg');
                break;
            case 'png':
                header('content-type:image/png');
                break;
            default:
                $this->_log('Can not set header.');
                break;
        }
        return true;
    }
   
    /**
     * change the image size
     *
     * @param int $width
     * @param int $height
     * @return boolean
     */
    function changeSize($width, $height = -1) {
        if(!is_resource($this->imageResource)) {
			$this->_log('不能改变图片的尺寸,可能是你没有设置图片来源.');
			return false;
        }
        $s_width = $this->imageInfo[0];
        $s_height = $this->imageInfo[1];
        $width = intval($width);
        $height = intval($height);
       
        if($width <= 0 && $height <= 0) {
			$this->_log('图片宽度必须大于零.');
			return false;
		}
        if($height <= 0 && $width>0) {
            $height = ($s_height / $s_width) * $width;
        }
        if($width <= 0 && $height>0) {
            $width = ($s_width / $s_height) * $height;
        }
       
		if(function_exists("ImageCreateTrueColor")){
			$this->target = ImageCreateTrueColor($width, $height);
		}else{
			$this->target = ImageCreate($width, $height);
		}

		if(function_exists("ImageCopyResampled") && @ImageCopyResampled($this->target, $this->imageResource, 0, 0, 0, 0, $width, $height, $s_width, $s_height)){
			return true;
		}elseif(@imagecopyresized($this->target, $this->imageResource, 0, 0, 0, 0, $width, $height, $s_width, $s_height)){
			return true;
		}else{
			return false;
		}
    }
   
    /**
     * Add watermark
     *
     * @param string $image
     * @param int $app
     */
    function addWatermark($image, $app = 50) {
        if(file_exists($image) && is_file($image)) {
            $s_info = getimagesize($image);
        } else {
			$this->_log($image . '水印文件不存在.');
			return false;
        }

        $r_width = $s_info[0];
        $r_height = $s_info[1];

        if($r_width > $this->imageInfo[0]) {$this->_log('水印图片必须小于目标图片');  return false;}
        if($r_height > $this->imageInfo[1]){$this->_log('水印图片必须小于目标图片');  return false;}
       
        switch ($s_info['mime']) {
            case 'image/gif':
                $resource = imagecreatefromgif($image);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $resource = imagecreatefromjpeg($image);
                break;
            case 'image/png':
                $resource = imagecreatefrompng($image);
                break;
            default:
				$this->_log($s_info['mime'] .'类型不能作为水印来源.');
				return false;
                break;
        }
       
        $this->target = &$this->imageResource;
        imagecopymerge($this->target, $resource, $this->imageInfo[0] - $r_width - 5, $this->imageInfo[1] - $r_height - 5, 0,0 ,$r_width, $r_height, $app);
        imagedestroy($resource);
        unset($resource);
    }
   
    /**
     * create image
     *
     * @param string $name
     * @return boolean
     */
    function create($name = NULL) {
        $function = $this->createFunc;
        if($this->target != NULL && is_resource($this->target)) {
            if($name != NULL) {
                $function($this->target, $name);
            } else {
                $function($this->target);
            }
            return true;
        } else if($this->imageResource != NULL && is_resource($this->imageResource)) {
            if($name != NULL) {
                $function($this->imageResource, $name);
            } else {
                $function($this->imageResource);
            }
            return true;
        } else {
            $this->_log('不能创建图片,原因可能是没有设置图片来源.');
			return false;
        }
    }
   
    /**
     * free resource
     *
     */
    function free() {
        if(is_resource($this->imageResource)) {
            @imagedestroy($this->imageResource);
        }
        if(is_resource($this->target)) {
            @imagedestroy($this->target);
        }
    }

	function _log($str) {
		$fo=fopen("logger.txt","a");
		$str=$str."\r\n";
		fwrite($fo,$str);
		fclose($fo);
		//exit();
		//exit($str);
	}
}
?>