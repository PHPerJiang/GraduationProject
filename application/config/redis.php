<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: jiangyu01
 * Date: 2018/12/19
 * Time: 14:13
 */

$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '127.0.0.1';
$config['auth'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;