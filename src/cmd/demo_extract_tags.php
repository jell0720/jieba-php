#!/usr/bin/php
<?php
/**
 * demo_extract_tags.php
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /src/cmd/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  MIT Licence
 * @version  GIT: <fukuball/jieba-php>
 * @link     https://github.com/fukuball/jieba-php
 */
ini_set('memory_limit', '600M');

require_once dirname(dirname(__FILE__))."/vendor/multi-array/MultiArray.php";
require_once dirname(dirname(__FILE__))."/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once dirname(dirname(__FILE__))."/class/Jieba.php";
require_once dirname(dirname(__FILE__))."/class/Finalseg.php";
require_once dirname(dirname(__FILE__))."/class/JiebaAnalyse.php";
use Fukuball\Jieba;
use Fukuball\Finalseg;
use Fukuball\JiebaAnalyse;
Jieba::init(array('mode'=>'test','dict'=>'samll'));
Finalseg::init();
JiebaAnalyse::init();

$top_k = 10;
$content = file_get_contents(dirname(dirname(__FILE__))."/dict/lyric.txt", "r");

$tags = JiebaAnalyse::extractTags($content, $top_k);

var_dump($tags);
?>