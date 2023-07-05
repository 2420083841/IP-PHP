<?php
header("Content-type: image/jpg");
date_default_timezone_set('Asia/Shanghai');
header("Access-Control-Allow-Origin:*");

//参数设置
$txkey = '1WWBZ-LQ163-ZC53T-32N2V-W1HR3-1YFKF';//需要填写自己的KET

$ip = ipv4();//获取用户真实ip
$_type = isset($_GET['type']) ? $_GET['type'] : '';//获取图片参数
$images = array(
    array("file" => "0.jpg", "color" => array(0, 0, 0)),// 经典女孩
    array("file" => "1.jpg", "color" => array(23.5, 13.3, 7.5)), // jk美女
    array("file" => "2.jpg", "color" => array(55.7, 39.6, 41.2)), // x美女
    array("file" => "3.jpg", "color" => array(79.2, 64.7, 63.9)), // 美女
    array("file" => "4.jpg", "color" => array(34.1, 54.1, 23.1)), // 绿色美女
    array("file" => "5.jpg", "color" => array(35.3, 56.9, 48.2)), // 美女
);
shuffle($images); // 随机排序数组
$index = 0; // 记录当前选择的图片索引
function get_image()
{
    global $images, $index;
    $image = $images[$index];
    $index = ($index + 1) % count($images); // 循环选择图片
    return $image;
}

$random = get_image();
$img = array(
    array("file" => "0.jpg", "color" => array(0, 0, 0)),// 经典女孩
    array("file" => "1.jpg", "color" => array(23.5, 13.3, 7.5)), // jk美女
    array("file" => "2.jpg", "color" => array(55.7, 39.6, 41.2)), // x美女
    array("file" => "3.jpg", "color" => array(79.2, 64.7, 63.9)), // 美女
    array("file" => "4.jpg", "color" => array(34.1, 54.1, 23.1)), // 绿色美女
    array("file" => "5.jpg", "color" => array(35.3, 56.9, 48.2)), // 美女
);
if (isset($_GET['type']) && $_type == 0) {
    $selected_image = $img[0]['file'];
    $selected_color = $img[0]['color'];
} elseif (isset($_GET['type']) && $_type == 1) {
    $selected_image = $img[1]['file'];
    $selected_color = $img[1]['color'];
} elseif (isset($_GET['type']) && $_type == 2) {
    $selected_image = $img[2]['file'];
    $selected_color = $img[2]['color'];
} elseif (isset($_GET['type']) && $_type == 3) {
    $selected_image = $img[3]['file'];
    $selected_color = $img[3]['color'];
} elseif (isset($_GET['type']) && $_type == 4) {
    $selected_image = $img[4]['file'];
    $selected_color = $img[4]['color'];
} elseif (isset($_GET['type']) && $_type == 5) {
    $selected_image = $img[5]['file'];
    $selected_color = $img[5]['color'];
} else {
    $selected_image = $random['file'];
    $selected_color = $random['color'];
}

function getLocationByIp($ip,$txkey)
{
    // 创建 Redis 实例
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
//    $redis->auth('your_password');//Redis密码
    // 构建缓存键名，使用手机号作为缓存键名
    $cacheKey = 'txip_' . substr(md5($ip), 8, 16);
    // 检查是否存在对应缓存
    if ($redis->exists($cacheKey)) {
        // 如果缓存存在，则从 Redis 中读取缓存数据
        $resl = $redis->get($cacheKey);
    } else {
        $url = 'https://apis.map.qq.com/ws/location/v1/ip?ip=' . $ip . '&key=' . $txkey;
        // 初始化
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // 执行
        $resl = curl_exec($curl);
        curl_close($curl);
        // 将数据写入缓存，并设置过期时间为 86400 秒
        $redis->setex($cacheKey, 86400, $resl);
    }
    return $resl;
}

function tqIp($code)
{
    $userAgents = [
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36",
        "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/603.2.4 (KHTML, like Gecko) Version/10.1.1 Safari/603.2.4",
        "Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko",
    ];
    $ua = $userAgents[array_rand($userAgents)];
    $ipp = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
    $header[] = "Origin: https://www.baidu.com";
    $header[] = "Referer: https://www.baidu.com/s?ie=UTF-8&wd=ip";
    $header[] = "CLIENT-IP:" . $ipp;
    $header[] = "X-FORWARDED-FOR:" . $ipp;
    $url = 'https://www.toutiao.com/stream/widget/local_weather/data/?city=' . urlencode($code);
    // 初始化
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_USERAGENT, $ua);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    // 执行
    $resl = curl_exec($curl);
    curl_close($curl);
    return $resl;
}

function ipv4()
{
    static $ip = '';
    $ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] as $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip) && filter_var($xip, FILTER_VALIDATE_IP)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

function get_bro()
{
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
    if (stripos($sys, "Firefox/") > 0) {
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
        $exp[0] = "Firefox";
        $exp[1] = $b[1];  //获取火狐浏览器的版本号
    } elseif (stripos($sys, "MicroMessenger") > 0) {
        $exp[0] = "微信";
        preg_match("/MicroMessenger\/([\d\.]+)/", $sys, $MicroMessenger);
        $exp[1] = $MicroMessenger[1];  //获取微信浏览器的版本号
    } elseif (stripos($sys, "Chrome") > 0) {
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
        $exp[0] = "Chrome";
        $exp[1] = $google[1];  //获取google chrome的版本号
    } elseif (stripos($sys, "Edge") > 0) {
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
        $exp[0] = "Edge";
        $exp[1] = $Edge[1];
    } elseif (stripos($sys, "Maxthon") > 0) {
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
        $exp[0] = "傲游";
        $exp[1] = $aoyou[1];
    } elseif (stripos($sys, "MSIE") > 0) {
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
        $exp[0] = "IE";
        $exp[1] = $ie[1];  //获取IE的版本号
    } elseif (stripos($sys, "OPR") > 0) {
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
        $exp[0] = "Opera";
        $exp[1] = $opera[1];
    } elseif (stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0) {
        preg_match("/rv:([\d\.]+)/", $sys, $IE);
        $exp[0] = "IE";
        $exp[1] = $IE[1];
    } else {
        $exp[0] = "未知浏览器";
        $exp[1] = "";
    }
    return $exp[0] . '(' . $exp[1] . ')';
}

//操作系统
function get_os()
{
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;
    if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
        $os = 'Windows 7';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
        $os = 'Windows 8';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)) {
        $os = 'Windows 10';#添加win10判断
    } else if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
        $os = 'Windows 95';
    } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
        $os = 'Windows ME';
    } else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)) {
        $os = 'Windows 98';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
        $os = 'Windows Vista';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
        $os = 'Windows XP';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
        $os = 'Windows 2000';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
        $os = 'Windows NT';
    } else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)) {
        $os = 'Windows 32';
    } else if (preg_match('/linux/i', $agent)) {
        $os = 'Linux';
        if (preg_match('/Android.([0-9. _]+)/i', $agent, $matches)) {
            $os = 'Android';
        } elseif (preg_match('#Ubuntu#i', $agent)) {
            $os = 'Ubuntu';
        } elseif (preg_match('#Debian#i', $agent)) {
            $os = 'Debian';
        } elseif (preg_match('#Fedora#i', $agent)) {
            $os = 'Fedora';
        }
    } else if (preg_match('/unix/i', $agent)) {
        $os = 'Unix';
    } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'SunOS';
    } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'IBM OS/2';
    } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
        $os = 'Macintosh';
    } else if (preg_match('/PowerPC/i', $agent)) {
        $os = 'PowerPC';
    } else if (preg_match('/AIX/i', $agent)) {
        $os = 'AIX';
    } else if (preg_match('/HPUX/i', $agent)) {
        $os = 'HPUX';
    } else if (preg_match('/NetBSD/i', $agent)) {
        $os = 'NetBSD';
    } else if (preg_match('/BSD/i', $agent)) {
        $os = 'BSD';
    } else if (preg_match('/OSF1/i', $agent)) {
        $os = 'OSF1';
    } else if (preg_match('/IRIX/i', $agent)) {
        $os = 'IRIX';
    } else if (preg_match('/FreeBSD/i', $agent)) {
        $os = 'FreeBSD';
    } else if (preg_match('/teleport/i', $agent)) {
        $os = 'teleport';
    } else if (preg_match('/flashget/i', $agent)) {
        $os = 'flashget';
    } else if (preg_match('/webzip/i', $agent)) {
        $os = 'webzip';
    } else if (preg_match('/offline/i', $agent)) {
        $os = 'offline';
    } else {
        $os = '未知操作系统';
    }
    return $os;
}

$bro = get_bro();
$os = get_os();
$txresl = getLocationByIp($ip,$txkey);
$arr = json_decode($txresl, true);
$gdresl = tqIp($arr['result']['ad_info']['city']);
$gdresl = json_decode($gdresl, true);
if (empty($arr['result']['ad_info']['city']) || empty($gdresl['data']['weather']['current_condition'])) {
    header('Content-Type: image/jpg');
    readfile('error.jpg');
}
function generateImage($selected_image, $selected_color, $arr, $gdresl, $ip, $os, $bro)
{
    $im = imagecreatefromjpeg($selected_image);
    $weekarray = array("日", "一", "二", "三", "四", "五", "六");
    $red = round($selected_color[0] * 2.55);
    $green = round($selected_color[1] * 2.55);
    $blue = round($selected_color[2] * 2.55);
    $color = imagecolorallocate($im, $red, $green, $blue);
    $font = 'HarmonyOS_Sans_SC_Medium.ttf';
    // 输出文字
    imagettftext($im, 16, 0, 18, 40, $color, $font, '欢迎来自 ' . $arr['result']['ad_info']['province'] . '-' . $arr['result']['ad_info']['city'] . ' ' . $gdresl['data']['weather']['current_condition'] . ' ' . $gdresl['data']['weather']['current_temperature'] . '℃');
    imagettftext($im, 16, 0, 18, 72, $color, $font, '今天日期 ' . date('Y年n月j日') . " 星期" . $weekarray[date("w")]);
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        imagettftext($im, 16, 0, 10, 104, $color, $font, '您访问IP是');
        imagettftext($im, 8, 0, 120, 100, $color, $font, $ip);
    } else {
        imagettftext($im, 16, 0, 10, 104, $color, $font, '您访问IP是');
        imagettftext($im, 16, 0, 150, 104, $color, $font, $ip);
    }
    if (strlen($os) < 12) {
        imagettftext($im, 16, 0, 10, 140, $color, $font, '您使用的是 ');
        imagettftext($im, 16, 0, 130, 140, $color, $font, $os . ' 操作系统');
    } else {
        imagettftext($im, 16, 0, 10, 140, $color, $font, '您使用的是 ' . $os . ' 操作系统');
    }
    imagettftext($im, 16, 0, 10, 175, $color, $font, '您使用的是' . $bro . '浏览器');

    ob_start();// 将图片保存为二进制数据
    imagepng($im);
    $img = ob_get_clean();
    ImageDestroy($im);// 销毁图片
    return $img;// 返回图片的二进制数据
}

$img = generateImage($selected_image, $selected_color, $arr, $gdresl, $ip, $os, $bro);
echo $img;