<?php
/**
 *文件从上传类配置
 */
 $config['upload_path'] = FCPATH.'/assets/';     #文件上传目录 -- 项目根目录
 $config['upload_max_size'] = 10241000;       #上传文件大小限制,单位b
 $config['upload_mime'] = array('image/jpeg','image/png','image/gif');     #允许上传的文件类型