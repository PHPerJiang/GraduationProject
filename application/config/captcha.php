<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 验证码类配置
 */

$config['captcha_width'] = 80;       #画布宽度
$config['captcha_height'] = 30;      #画布高度
$config['captcha_codeNum'] = 4;      #验证码个数
$config['captcha_code_range'] = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";   #验证码取值范围