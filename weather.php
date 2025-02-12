<?php 

header("Access-Control-Allow-Origin: *"); 
header('Content-Type: application/json');

function get() { 

$headers = [ 

'Host: d1.weather.com.cn', 

'Referer: https://www.weather.com.cn/', 

'sec-fetch-dest: json' 

]; 

$dateTime = new DateTime(); 

$milliseconds = $dateTime->format('U') * 1000 + $dateTime->format('v'); 

$options = [ 

"http" => [ 

"header" => implode("\r\n", $headers) 

] 

]; 

$context = stream_context_create($options); 

$output = file_get_contents('https://d1.weather.com.cn/weather_index/101281601.html?_=' . $milliseconds,false, $context); 

return $output . '$$'; 

} 

$output = get(); 

$startChar = 'var dataSK ='; 

$endChar = ';var dataZS ='; 

$startPos = strpos($output, $startChar); 

$endPos = strpos($output, $endChar, $startPos); 

// 检查位置是否有效 

if ($startPos !== false && $endPos !== false) { 

// 从开始字符的下一个位置开始截取，长度为结束字符的位置减去开始字符的位置 

$startPos += 1; // 从开始字符的下一个位置开始 

$length = $endPos - $startPos; // 计算长度 

// 截取字符串 

$substring1 = str_replace('ar dataSK =','',substr($output, $startPos, $length)); 

} 

$startChar = 'var dataZS ='; 

$endChar = ';var fc ='; 

$startPos = strpos($output, $startChar); 

$endPos = strpos($output, $endChar, $startPos); 

// 检查位置是否有效 

if ($startPos !== false && $endPos !== false) { 

// 从开始字符的下一个位置开始截取，长度为结束字符的位置减去开始字符的位置 

$startPos += 1; // 从开始字符的下一个位置开始 

$length = $endPos - $startPos; // 计算长度 

// 截取字符串 

$substring2 = str_replace('ar dataZS =','',substr($output, $startPos, $length)); 

} 

$startChar = 'var fc ='; 

$endChar = '$$'; 

$startPos = strpos($output, $startChar); 

$endPos = strpos($output, $endChar, $startPos); 

// 检查位置是否有效 

if ($startPos !== false && $endPos !== false) { 

// 从开始字符的下一个位置开始截取，长度为结束字符的位置减去开始字符的位置 

$startPos += 1; // 从开始字符的下一个位置开始 

$length = $endPos - $startPos; // 计算长度 

// 截取字符串 

$substring3 = str_replace('ar fc =','',substr($output, $startPos, $length)); 

} 

$a = json_decode($substring1,true); 

$b = json_decode($substring2,true); 

$c = json_decode($substring3,true); 

$result = json_encode(array( 

'city' => $a['cityname'], 

'temp' => $a['temp'], 

'WD' => $a['WD'], 

'WS' => $a['WS'], 

'SD' => $a['SD'], 

'time' => $a['time'], 

'weather' => $a['weather'], 

'date' => $a['date'], 

'ct_hint' => $b['zs']['ct_hint'], 

'gm_hint' => $b['zs']['gm_hint'], 

'uv_hint' => $b['zs']['uv_hint'], 

'xc_hint' => $b['zs']['xc_hint'], 

'data' => [array( 

'jt' => $c['f'][0]['fj'], //今天 

'jt_fc' => $c['f'][0]['fc'], 

'jt_fd' => $c['f'][0]['fd'], 

'jt_fe' => $c['f'][0]['fe'], 

'jt_fg' => $c['f'][0]['fg'], 

'img' => './img/' . $c['f'][0]['fa'] . '.png' 

),array( 

'jt' => $c['f'][1]['fj'], //明天 

'jt_fc' => $c['f'][1]['fc'], 

'jt_fd' => $c['f'][1]['fd'], 

'jt_fe' => $c['f'][1]['fe'], 

'jt_fg' => $c['f'][1]['fg'], 

'img' => './img/' . $c['f'][1]['fa'] . '.png' 

),array( 

'jt' => $c['f'][2]['fj'], //后天 

'jt_fc' => $c['f'][2]['fc'], 

'jt_fd' => $c['f'][2]['fd'], 

'jt_fe' => $c['f'][2]['fe'], 

'jt_fg' => $c['f'][2]['fg'], 

'img' => './img/' . $c['f'][2]['fa'] . '.png' 

),array( 

'jt' => $c['f'][3]['fj'], //大后天 

'jt_fc' => $c['f'][3]['fc'], 

'jt_fd' => $c['f'][3]['fd'], 

'jt_fe' => $c['f'][3]['fe'], 

'jt_fg' => $c['f'][3]['fg'], 

'img' => './img/' . $c['f'][3]['fa'] . '.png' 

),array( 

'jt' => $c['f'][4]['fj'], //大大后天 

'jt_fc' => $c['f'][4]['fc'], 

'jt_fd' => $c['f'][4]['fd'], 

'jt_fe' => $c['f'][4]['fe'], 

'jt_fg' => $c['f'][4]['fg'], 

'img' => './img/' . $c['f'][4]['fa'] . '.png' 

)] 

),JSON_UNESCAPED_UNICODE); 

echo $result; 

?>
