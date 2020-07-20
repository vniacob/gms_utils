<?php
//example
//$url = "http://maplestory.nexon.net/rankings/overall-ranking/legendary?pageIndex=1&character_name=Maze&search=true&region=eu&rebootIndex=2";
$url = "http://maplestory.nexon.net/rankings/overall-ranking/legendary?" . $_SERVER['QUERY_STRING'];

libxml_use_internal_errors(true);
$dom = new DomDocument;
$dom->loadHTMLFile($url);
$xpath = new DomXPath($dom);
$nodes = $xpath->query("/html/body/div[2]/div[2]/div/div/div[2]/div/div[2]/table");
$html = $nodes[0]->ownerDocument->saveXML($nodes[0]);
//header("Content-type: text/html");
//echo $html;

header("Content-type: application/json");
$xml = parseXml($html);
echo '{"' . $xml->thead[0]->tr[0]->th[2] . '":"' . trim($xml->tbody[0]->tr[0]->td[2]) . '"'; // NAME
echo ',"' . $xml->thead[0]->tr[0]->th[0] . '":'  . trim($xml->tbody[0]->tr[0]->td[0]); // RANK
echo ',"' . $xml->thead[0]->tr[0]->th[1] . '":"' . trim($xml->tbody[0]->tr[0]->td[1]->img[0]->attributes()['src']) . '"'; // CHARACTER IMG
echo ',"' . $xml->thead[0]->tr[0]->th[3] . '":"' . trim($xml->tbody[0]->tr[0]->td[3]->a[0]->attributes()['title']) . '"'; // WORLD
echo ',"' . $xml->thead[0]->tr[0]->th[4] . '":"' . trim($xml->tbody[0]->tr[0]->td[4]->img[0]->attributes()['title']) . '"'; // JOB
echo ',"' . $xml->thead[0]->tr[0]->th[4] . '_SRC":"' . trim($xml->tbody[0]->tr[0]->td[4]->img[0]->attributes()['src']) . '"'; // JOB IMG
$lvlmove = trim($xml->tbody[0]->tr[0]->td[5]);
$lvl = trim(substr($lvlmove, 0, strpos($lvlmove, " ")));
$exp = trim(substr($lvlmove, strpos($lvlmove, "(") + 1));
$exp = substr($exp, 0, strpos($exp, ")"));
$move = $xml->tbody[0]->tr[0]->td[5]->div[0];
if (strpos($move->attributes()['class'],'rank-up') !== false) {
  $move = "+" . $move;
}
else if (strpos($move->attributes()['class'],'rank-down') !== false) {
  $move = "-" . $move;
}
echo ',"LEVEL":' . $lvl;
echo ',"EXP":' . $exp;
echo ',"MOVE":"' . $move . '"';
echo '}';

function parseXml($html) {
  $html = trim(str_replace('"', "'", $html));
  $simpleXml = simplexml_load_string($html);
  return $simpleXml;
}
?>
