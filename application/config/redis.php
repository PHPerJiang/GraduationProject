<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * redis配置
 * @author jiangyu
 * $time 18.12.20
 */

$config['redis_socket_type'] = 'tcp'; //`tcp` or `unix`
$config['redis_socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['redis_host'] = '127.0.0.1';
$config['redis_auth'] = NULL;
$config['redis_port'] = 6379;
$config['redis_timeout'] = 0;