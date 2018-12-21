<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 验证码类配置
 */

$config['width'] = 80;       #画布宽度
$config['height'] = 30;      #画布高度
$config['codeNum'] = 4;      #验证码个数
$config['code'] = 4;         #验证码个数
$config['code_range'] = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";   #验证码取值范围