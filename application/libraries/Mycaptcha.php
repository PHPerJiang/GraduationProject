<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 验证码操作类
 * @author jiangyu
 * @time 18.12.21
 */
class Mycaptcha
{
    private $width;         #画布宽度
    private $height;        #画布高度
    private $codeNum;       #验证码个数
    private $code;          #验证码
    private $im;            #画布
    private $code_range;   #验证码范围

    function __construct(){
        $CI = &get_instance();
        $this->width = $CI->config->item('captcha_width');
        $this->height = $CI->config->item('captcha_height');
        $this->codeNum = $CI->config->item('captcha_codeNum');
        $this->code_range = $CI->config->item('captcha_code_range');
    }

    function showImg(){
        #创建画布
        $this->createImg();
        #设置干扰元素
        $this->setDisturb();
        #设置验证码
        $this->setCaptcha();
        #输出图片
        $this->outputImg();
    }

    /**
     * 获取验证码
     * @return mixed
     */
    function getCaptcha(){
        $this->createCode();
        return $this->code;
    }

    /**
     * 创建画布
     */
    private function createImg(){
        $this->im = imagecreatetruecolor($this->width, $this->height);
        $bgColor = imagecolorallocate($this->im, 0, 0, 0);
        imagefill($this->im, 0, 0, $bgColor);
    }

    /**
     * 设置干扰元素
     */
    private function setDisturb(){
        $area = ($this->width * $this->height) / 20;
        $disturbNum = ($area > 250) ? 250 : $area;
        #加入点干扰
        for ($i = 0; $i < $disturbNum; $i++) {
            $color = imagecolorallocate($this->im, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($this->im, rand(1, $this->width - 2), rand(1, $this->height - 2), $color);
        }
        #加入弧线干扰
        for ($i = 0; $i <= 5; $i++) {
            $color = imagecolorallocate($this->im, rand(128, 255), rand(125, 255), rand(100, 255));
            imagearc($this->im, rand(0, $this->width), rand(0, $this->height), rand(30, 300), rand(20, 200), 50, 30, $color);
        }
    }

    /**
     * 创建验证码
     */
    private function createCode(){
        $str = $this->code_range;
        for ($i = 0; $i < $this->codeNum; $i++) {
            $this->code .= $str{rand(0, strlen($str) - 1)};
        }
    }

    /**
     * 设置验证码
     */
    private function setCaptcha(){
        for ($i = 0; $i < $this->codeNum; $i++) {
            $color = imagecolorallocate($this->im, rand(50, 250), rand(100, 250), rand(128, 250));
            $size = rand(floor($this->height / 5), floor($this->height / 3));
            $x = floor($this->width / $this->codeNum) * $i + 5;
            $y = rand(0, $this->height - 20);
            imagechar($this->im, $size, $x, $y, $this->code{$i}, $color);
        }
    }

    /**
     * 输出验证码到浏览器
     */
    private function outputImg(){
        if (imagetypes() & IMG_JPG) {
            header('Content-type:image/jpeg');
            imagejpeg($this->im);
        } elseif (imagetypes() & IMG_GIF) {
            header('Content-type: image/gif');
            imagegif($this->im);
        } elseif (imagetype() & IMG_PNG) {
            header('Content-type: image/png');
            imagepng($this->im);
        } else {
            die("Don't support image type!");
        }
    }

}