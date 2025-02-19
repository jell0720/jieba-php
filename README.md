jieba-php
=========
[![Build Status](https://travis-ci.org/fukuball/jieba-php.svg?branch=master)](https://travis-ci.org/fukuball/jieba-php)
[![Coverage Status](https://coveralls.io/repos/fukuball/jieba-php/badge.svg?branch=master)](https://coveralls.io/r/fukuball/jieba-php?branch=master)
[![codecov.io](http://codecov.io/github/fukuball/jieba-php/coverage.svg?branch=master)](http://codecov.io/github/fukuball/jieba-php?branch=master)
[![Latest Stable Version](https://poser.pugx.org/fukuball/jieba-php/v/stable.png)](https://packagist.org/packages/fukuball/jieba-php)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/fukuball/jieba-php/master/LICENSE)

"結巴"中文分詞：做最好的 PHP 中文分詞、中文斷詞組件，目前翻譯版本為 jieba-0.19 版本，未來再慢慢往上升級，效能也需要再改善，請有興趣的開發者一起加入開發！若想使用 Python 版本請前往 [fxsjy/jieba](https://github.com/fxsjy/jieba)

線上展示
========
* 網站網址：[http://jieba-php.fukuball.com](http://jieba-php.fukuball.com)
* 網站原始碼：[https://github.com/fukuball/jieba-php.fukuball.com](https://github.com/fukuball/jieba-php.fukuball.com)

Feature
========
* 支持兩種分詞模式：
* 1）默認模式，試圖將句子最精確地切開，適合文本分析；
* 2）全模式，把句子中所有的可以成詞的詞語都掃描出來，適合搜索引擎。（需要充足的字典）

Usage
========
* 自動安裝：使用 composer 安裝後，透過 autoload 引用

代碼示例

```php
composer require fukuball/jieba-php:dev-master
```

代碼示例

```php
require_once "/path/to/your/vendor/autoload.php";
```

* 手動安裝：將 jieba-php 放置適當目錄後，透過 require_once 引用

代碼示例

```php
require_once "/path/to/your/vendor/multi-array/MultiArray.php";
require_once "/path/to/your/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once "/path/to/your/class/Jieba.php";
require_once "/path/to/your/class/Finalseg.php";
```

Algorithm
========
* 基於 Trie 樹結構實現高效的詞圖掃描，生成句子中漢字構成的有向無環圖（DAG)
* 採用了記憶化搜索實現最大概率路徑的計算, 找出基於詞頻的最大切分組合
* 對於未登錄詞，採用了基於漢字位置概率的模型，使用了 Viterbi 算法
* BEMS 的解釋 [https://github.com/fxsjy/jieba/issues/7](https://github.com/fxsjy/jieba/issues/7)

Interface
========
* 組件只提供 jieba.cut 方法用於分詞
* cut 方法接受兩個輸入參數: 1) 第一個參數為需要分詞的字符串 2）cut_all 參數用來控制分詞模式
* 待分詞的字符串可以是 utf-8 字符串
* jieba.cut 返回的結構是一個可迭代的 array

功能 1)：分词
============

代碼示例 (Tutorial)

```php
ini_set('memory_limit', '1024M');

require_once "/path/to/your/vendor/multi-array/MultiArray.php";
require_once "/path/to/your/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once "/path/to/your/class/Jieba.php";
require_once "/path/to/your/class/Finalseg.php";
use Fukuball\Jieba;
use Fukuball\Finalseg;
Jieba::init();
Finalseg::init();

$seg_list = Jieba::cut("怜香惜玉也得要看对象啊！");
var_dump($seg_list);

seg_list = jieba.cut("我来到北京清华大学", true)
print "Full Mode:", "/ ".join(seg_list) #全模式

seg_list = jieba.cut("我来到北京清华大学", false)
print "Default Mode:", "/ ".join(seg_list) #默認模式

seg_list = jieba.cut("他来到了网易杭研大厦")
print ", ".join(seg_list)
```

Output:

```php
array(7) {
  [0]=>
  string(12) "怜香惜玉"
  [1]=>
  string(3) "也"
  [2]=>
  string(3) "得"
  [3]=>
  string(3) "要"
  [4]=>
  string(3) "看"
  [5]=>
  string(6) "对象"
  [6]=>
  string(3) "啊"
}
Full Mode:
array(15) {
  [0]=>
  string(3) "我"
  [1]=>
  string(3) "来"
  [2]=>
  string(6) "来到"
  [3]=>
  string(3) "到"
  [4]=>
  string(3) "北"
  [5]=>
  string(6) "北京"
  [6]=>
  string(3) "京"
  [7]=>
  string(3) "清"
  [8]=>
  string(6) "清华"
  [9]=>
  string(12) "清华大学"
  [10]=>
  string(3) "华"
  [11]=>
  string(6) "华大"
  [12]=>
  string(3) "大"
  [13]=>
  string(6) "大学"
  [14]=>
  string(3) "学"
}
Default Mode:
array(4) {
  [0]=>
  string(3) "我"
  [1]=>
  string(6) "来到"
  [2]=>
  string(6) "北京"
  [3]=>
  string(12) "清华大学"
}
array(6) {
  [0]=>
  string(3) "他"
  [1]=>
  string(6) "来到"
  [2]=>
  string(3) "了"
  [3]=>
  string(6) "网易"
  [4]=>
  string(6) "杭研"
  [5]=>
  string(6) "大厦"
}
(此處，“杭研“並沒有在詞典中，但是也被 Viterbi 算法識別出來了)
```

功能 2)：添加自定義詞典
====================

* 開發者可以指定自己自定義的詞典，以便包含 jieba 詞庫裡沒有的詞。雖然 jieba 有新詞識別能力，但是自行添加新詞可以保證更高的正確率
* 用法： Jieba::loadUserDict(file_name) # file_name 為自定義詞典的絕對路徑
* 詞典格式和 dict.txt 一樣，一個詞佔一行；每一行分為兩部分，一部分為詞語，另一部分為詞頻，用空格隔開
* 範例：

  云计算 5
  李小福 2
  创新办 3

  之前： 李小福 / 是 / 创新 / 办 / 主任 / 也 / 是 / 云 / 计算 / 方面 / 的 / 专家 /
  加載自定義詞庫後：　李小福 / 是 / 创新办 / 主任 / 也 / 是 / 云计算 / 方面 / 的 / 专家 /


功能 3)：關鍵詞提取
==============
* JiebaAnalyse::extractTags($content, $top_k)
* content 為待提取的文本
* top_k 為返回幾個權重最大的關鍵詞，默認值為20

代碼示例 (關鍵詞提取)

```php
ini_set('memory_limit', '600M');

require_once "/path/to/your/vendor/multi-array/MultiArray.php";
require_once "/path/to/your/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once "/path/to/your/class/Jieba.php";
require_once "/path/to/your/class/Finalseg.php";
require_once "/path/to/your/class/JiebaAnalyse.php";
use Fukuball\Jieba;
use Fukuball\Finalseg;
use Fukuball\JiebaAnalyse;
Jieba::init(array('mode'=>'test','dict'=>'samll'));
Finalseg::init();
JiebaAnalyse::init();

$top_k = 10;
$content = file_get_contents("/path/to/your/dict/lyric.txt", "r");

$tags = JiebaAnalyse::extractTags($content, $top_k);

var_dump($tags);
```

Output:
```php
array(10) {
  ["是否"]=>
  float(1.2196321889395)
  ["一般"]=>
  float(1.0032459890209)
  ["肌迫"]=>
  float(0.64654314660465)
  ["怯懦"]=>
  float(0.44762844339349)
  ["藉口"]=>
  float(0.32327157330233)
  ["逼不得已"]=>
  float(0.32327157330233)
  ["不安全感"]=>
  float(0.26548304656279)
  ["同感"]=>
  float(0.23929673812326)
  ["有把握"]=>
  float(0.21043366018744)
  ["空洞"]=>
  float(0.20598261709442)
}

```

License
=========
The MIT License (MIT)

Copyright (c) 2015 fukuball

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.