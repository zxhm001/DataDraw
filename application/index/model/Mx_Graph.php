<?php
namespace app\index\model;

use think\Model;
use think\Db;
include_once(ROOT_PATH."extend/MxGraph/mxServer.php");
use mxGraph;
use mxUtils;
use mxCodec;

class Mx_Graph extends Model{
    public static function ConvertToImage($filename)
    {
        
        $fp = fopen($filename, "r");
        $xml = "";
        while ($data = fread($fp, 4096))
		{
            $xml .= $data;
        }
        fclose($fp);
        // Creates graph with model
        $graph = new mxGraph();
        $parent = $graph->getDefaultParent();
        $doc = mxUtils::parseXml($xml);
        $codec = new mxCodec($doc);
        $obj = $codec->decode($doc->documentElement, $graph->getModel());
        $image = $graph->createImage(null, "#FFFFFF");
        header("Content-Type: image/png");
	    echo mxUtils::encodeImage($image);
    }
}