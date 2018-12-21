<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * redis配置
 * @author jiangyu
 * $time 18.12.20
 */

$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '127.0.0.1';
$config['auth'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;