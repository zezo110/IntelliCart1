<?php 
include('vandor/autoload.php');
use Sentiment\Analyzer;
$obj =new Analyzer();
$res=$obj->getSentiment('bad');
echo "<pre>";
print_r($res);





?>