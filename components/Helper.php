<?php

namespace components;

use Yii;

/**
 * Description of Helper
 *
 * @author Amit Handa
 */
class Helper
{
    public static function validateDateFormat($date)
    {
        return (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) ? true : false;
    }
    public static function getUserAgent()
    {
        return !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    public static function getDeviceType()
    {
        if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
           $user_ag = $_SERVER['HTTP_USER_AGENT'];
           if(preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis',$user_ag)){
              return 'Mobile';
           }
        }

        return 'Desktop';
    }

    public static function GetUserIp()
    {
        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        }
        else {
            $headers = $_SERVER;
        }

        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        }
        elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

    public static function GetDomain($domain, $debug = false)
    {
        $original = $domain = strtolower($domain);

        if (filter_var($domain, FILTER_VALIDATE_IP)) { return $domain; }

        $debug ? print('<strong style="color:green">&raquo;</strong> Parsing: '.$original) : false;

        $arr = array_slice(array_filter(explode('.', $domain, 4), function($value){
            return $value !== 'www';
        }), 0); //rebuild array indexes

        if (count($arr) > 2)
        {
            $count = count($arr);
            $_sub = explode('.', $count === 4 ? $arr[3] : $arr[2]);

            $debug ? print(" (parts count: {$count})") : false;

            if (count($_sub) === 2) // two level TLD
            {
                $removed = array_shift($arr);
                if ($count === 4) // got a subdomain acting as a domain
                {
                    $removed = array_shift($arr);
                }
                $debug ? print("<br>\n" . '[*] Two level TLD: <strong>' . join('.', $_sub) . '</strong> ') : false;
            }
            elseif (count($_sub) === 1) // one level TLD
            {
                $removed = array_shift($arr); //remove the subdomain

                if (strlen($_sub[0]) === 2 && $count === 3) // TLD domain must be 2 letters
                {
                    array_unshift($arr, $removed);
                }
                else
                {
                    // non country TLD according to IANA
                    $tlds = array(
                        'aero',
                        'arpa',
                        'asia',
                        'biz',
                        'cat',
                        'com',
                        'coop',
                        'edu',
                        'gov',
                        'info',
                        'jobs',
                        'mil',
                        'mobi',
                        'museum',
                        'name',
                        'net',
                        'org',
                        'post',
                        'pro',
                        'tel',
                        'travel',
                        'xxx',
                    );

                    if (count($arr) > 2 && in_array($_sub[0], $tlds) !== false) //special TLD don't have a country
                    {
                        array_shift($arr);
                    }
                }
                $debug ? print("<br>\n" .'[*] One level TLD: <strong>'.join('.', $_sub).'</strong> ') : false;
            }
            else // more than 3 levels, something is wrong
            {
                for ($i = count($_sub); $i > 1; $i--)
                {
                    $removed = array_shift($arr);
                }
                $debug ? print("<br>\n" . '[*] Three level TLD: <strong>' . join('.', $_sub) . '</strong> ') : false;
            }
        }
        elseif (count($arr) === 2)
        {
            $arr0 = array_shift($arr);

            if (strpos(join('.', $arr), '.') === false
                && in_array($arr[0], array('localhost','test','invalid')) === false) // not a reserved domain
            {
                $debug ? print("<br>\n" .'Seems invalid domain: <strong>'.join('.', $arr).'</strong> re-adding: <strong>'.$arr0.'</strong> ') : false;
                // seems invalid domain, restore it
                array_unshift($arr, $arr0);
            }
        }

        $debug ? print("<br>\n".'<strong style="color:gray">&laquo;</strong> Done parsing: <span style="color:red">' . $original . '</span> as <span style="color:blue">'. join('.', $arr) ."</span><br>\n") : false;

        return join('.', $arr);
    }
    
    /**
     * Download file from remote server and save on local server
     * @param type $downloadFilePath
     * @param type $saveFilePath
     * @return boolean
     * @throws \Exception
     */
    public static function DownloadRemoteFile($downloadFilePath, $saveFilePath)
    {
        try {

            $ch = curl_init($downloadFilePath);
            if (!$ch) {
                return false;
            }
            $fp = fopen($saveFilePath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            return $saveFilePath;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function getPlaceholdItImgUrl($size = '200x200', $color = 'EFEFEF', $text = 'no image', $textColor = 'AAAAAA')
    {
        $url = 'https://www.placehold.it/' . $size;
        if ($color != '') {
            $url .= '/' . $color;
        }
        if ($text != '') {
            $url .= '/'. $textColor .'&text=' . urlencode($text);
        }
        
        return $url;
    }
    
    public static function removeInvisibleContent($html)
    {
        return 
            preg_replace(
                array(
                    // Remove invisible content
                    '@<head[^>]*?>.*?</head>@siu',
                    '@<style[^>]*?>.*?</style>@siu',
                    '@<script[^>]*?.*?</script>@siu',
                    '@<object[^>]*?.*?</object>@siu',
                    '@<embed[^>]*?.*?</embed>@siu',
                    '@<applet[^>]*?.*?</applet>@siu',
                    '@<noframes[^>]*?.*?</noframes>@siu',
                    '@<noscript[^>]*?.*?</noscript>@siu',
                    '@<noembed[^>]*?.*?</noembed>@siu',
                ),
                array(
                    ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '
                ),
                $html 
            );
    }
    
    /**
    * excerpt first paragraph from html content
    * 
    **/
    public static function excerptParagraph($html, $max_char = 100, $trail='...' )
    {
        $html = self::removeInvisibleContent($html);
        
        // temp var to capture the p tag(s)
        $matches= array();
        if ( preg_match( '/<p(.*)>[^>\w]+<\/p>/', $html, $matches) )
        {
            // found <p></p>
            $p = strip_tags($matches[0]);
        } else {
            $p = strip_tags($html);
        }

        $p = trim(str_replace('&nbsp;', ' ', $p));
        $p = preg_replace('/\s+/', ' ', $p);
        
        if ( strlen( $p ) <= $max_char ) { $trail = ''; }
        
        //shorten without cutting words
        $p = self::shortStr($p, $max_char );

        // remove trailing comma, full stop, colon, semicolon, 'a', 'A', space
        $p = rtrim($p, ',.;: aA' );

        // return nothing if just spaces or too short
        if (ctype_space($p) || $p=='' || strlen($p)<10) { return ''; }
        
        //add space after a period (.)
        $p = preg_replace('/((?<=[A-Za-z0-9])\.(?=[A-Za-z]{2})|(?<=[A-Za-z]{2})\.(?=[A-Za-z0-9]))/', '. ', $p);
        
        return $p.$trail;
    }
    //

    /**
    * shorten string but not cut words
    * 
    **/
    public static function shortStr( $str, $len, $cut = false, $ellipsis = false)
    {
        if ( strlen( $str ) <= $len ) { return $str; }
        $string = ( $cut ? (($ellipsis) ? substr( $str, 0, $len-2 ) : substr( $str, 0, $len ) ) : substr( $str, 0, strrpos( substr( $str, 0, $len ), ' ' ) ) );
        if ($ellipsis) {
           $string .= "..."; 
        }
        return $string;
    }
    
    public static function htmlToText($html, $maxLen = -1)
    {
        $html = self::removeInvisibleContent($html);
        
        $txtOnly = strip_tags($html);
        
        $txtOnly = trim(str_replace('&nbsp;', ' ', $txtOnly));
        $txtOnly = preg_replace('/\s+/', ' ', $txtOnly);
        
        $txtOnly = preg_replace('/((?<=[A-Za-z0-9])\.(?=[A-Za-z]{2})|(?<=[A-Za-z]{2})\.(?=[A-Za-z0-9]))/', '. ', $txtOnly);
        
        if($maxLen > 0) {
            $txtOnly = self::shortStr($txtOnly, $maxLen, false, true);
        }
        
        return $txtOnly;
    }
    
    public static function htmlEncode($content)
    {
        $txt = \yii\helpers\Html::encode($content);
        
        return $txt;
    }
    
    public static function stripeInlineStyles($content)
    {
        //Remove style attrib from tags
        $styleStripped = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content); //remove style=""
        $strippedContent = preg_replace("/(<[^>]+) style='.*?'/i", '$1', $styleStripped); //remove style=''
        
        return $strippedContent;
    }
    
    /**
     * Function to add rel="nofollow" to anchor tags
     * 
     * @param string $content
     */
    public static function addNoFollowToAnchors($content, $excludeDomains = [])
    {
        libxml_use_internal_errors(true);
        
        $dom = new \DOMDocument;
        
        $dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOXMLDECL);
        
        $anchors = $dom->getElementsByTagName('a');

        foreach($anchors as $anchor) { 
            $rel = array(); 
            $processNoFollow = FALSE;
            
            if ($anchor->hasAttribute('href') && ($href = $anchor->getAttribute('href')) !== '') {
                if (!preg_match('/' . implode('|', $excludeDomains) . '/', $href)) {
                    $processNoFollow = TRUE;
                }
            }
            
            if ($processNoFollow) {
                if ($anchor->hasAttribute('rel') && ($relAtt = $anchor->getAttribute('rel')) !== '') {
                    $rel = preg_split('/\s+/', trim($relAtt));
                }

                if (in_array('nofollow', $rel)) {
                    continue;
                }

                $rel[] = 'nofollow';
                $anchor->setAttribute('rel', implode(' ', $rel));
            }
        }

        return preg_replace("/(<\/?html>|<!DOCTYPE.+|<\/?body>)/", '', $dom->saveHTML());
    }
    
    public static function checkIfSpider()
    {
		if (!isset($_SERVER['HTTP_USER_AGENT']))
		{
			return FALSE;
		}
        
        $current = strtolower( $_SERVER['HTTP_USER_AGENT'] );
        
		// Array of known bot lowercase strings
        // Example: 'googlebot' will match 'Googlebot/2.1 (+http://www.googlebot.com/bot.html)'
        $bots = array(
            // List of Active crawlers & bots since October 2013 (imcomplete)
            // picked up from: http://user-agent-string.info/list-of-ua/bots
            // also: http://myip.ms/browse/web_bots/Known_Web_Bots_Web_Bots_2014_Web_Spider_List.html
            '200please.com/bot',
            '360spider',
            '80legs.com/webcrawler',
            'a6-indexer',
            'aboundex',
            'aboutusbot',
            'addsearchbot',
            'addthis.com',
            'adressendeutschland.de',
            'adsbot-google',
            'ahrefsbot',
            'aihitbot',
            'alexa site audit',
            'amznkassocbot',
            'analyticsseo.com',
            'antbot',
            'arabot',
            'archive.org_bot',
            'archive.orgbot',
            'askpeterbot',
            'backlinkcrawler',
            'baidu.com/search/spider.html',
            'baiduspider',
            'begunadvertising',
            'bingbot',
            'bingpreview',
            'bitlybot',
            'bixocrawler',
            'blekkobot',
            'blexbot',
            'brainbrubot',
            'browsershots',
            'bubing',
            'butterfly',
            'bufferbot',
            'careerbot',
            'catchbot',
            'ccbot',
            'cert figleafbot',
            'changedetection.com/bot.html',
            'chilkat',
            'claritybot',
            'classbot',
            'cliqzbot',
            'cms crawler',
            'coccoc',
            'compspybot',
            'crawler4j',        
            'crowsnest',
            'crystalsemanticsbot',
            'dataminr.com',
            'daumoa',
            'easouspider',
            'exabot',
            'exb language crawler',
            'ezooms',
            'facebookexternalhit',
            'facebookplatform',
            'fairshare',
            'feedfetcher',
            'feedly.com/fetcher.html',
            'feedlybot',
            'fetch',
            'flipboardproxy',
            'fyberspider',
            'genieo',
            'gigabot',
            'google page speed insights',
            'googlebot',
            'grapeshot',
            'hatena-useragent',
            'hubspot connect',
            'hubspot links crawler',
            'hosttracker.com',
            'ia_archiver',
            'icc-crawler',
            'ichiro',
            'immediatenet.com',
            'iltrovatore-setaccio',
            'infohelfer',
            'instapaper',
            'ixebot',
            'jabse.com crawler',
            'james bot',
            'jikespider',
            'jyxobot',
            'linkdex',
            'linkfluence',
            'loadimpactpageanalyzer',
            'luminate.com',
            'lycosa',
            'magpie-crawler',
            'mail.ru_bot',
            'meanpathbot',
            'mediapartners-google',
            'metageneratorcrawler',
            'metajobbot',
            'mj12bot',
            'mojeekbot',
            'msai.in',
            'msnbot-media',
            'musobot',
            'najdi.si',
            'nalezenczbot',
            'nekstbot',
            'netcraftsurveyagent',
            'netestate ne crawler',
            'netseer crawler',
            'nuhk',
            'obot',
            'omgilibot',
            'openwebspider',
            'panscient.com',
            'parsijoo',
            'plukkie',
            'proximic',
            'psbot',
            'qirina hurdler',
            'qualidator.com',
            'queryseekerspider',
            'readability',
            'rogerbot',
            'sbsearch',
            'scrapy',
            'search.kumkie.com',
            'searchbot',
            'searchmetricsbot',
            'semrushbot',
            'seocheckbot',
            'seoengworldbot',
            'seokicks-robot',
            'seznambot',
            'shareaholic.com/bot',
            'shopwiki.com/wiki/help:bot',
            'showyoubot',
            'sistrix',
            'sitechecker',
            'siteexplorer',
            'speedy spider',
            'socialbm_bot',
            'sogou web spider',
            'sogou',
            'sosospider',
            'spbot',
            'special_archiver',
            'spiderling',
            'spinn3r',
            'spreadtrum',
            'steeler',
            'suma spider',
            'surveybot',
            'suggybot',
            'svenska-webbsido',
            'teoma',
            'thumbshots',
            'tineye.com',
            'trendiction.com',
            'trendiction.de/bot',
            'turnitinbot',
            'tweetedtimes bot',
            'tweetmeme',
            'twitterbot',
            'uaslinkchecker',
            'umbot',
            'undrip bot',
            'unisterbot',
            'unwindfetchor',
            'urlappendbot',
            'vedma',
            'vkshare',
            'voilabot',
            'wbsearchbot',
            'wch web spider',
            'webcookies',
            'webcrawler at wise-guys dot nl',
            'webthumbnail',
            'wesee:search',
            'woko',
            'woobot',
            'woriobot',
            'wotbox',
            'y!j-bri',
            'y!j-bro',
            'y!j-brw',
            'y!j-bsc',
            'yacybot',
            'yahoo! slurp',
            'yahooysmcm',
            'yandexbot',
            'yats',
            'yeti',
            'yioopbot',
            'yodaobot',
            'youdaobot',
            'zb-1',
            'zeerch.com/bot.php',
            'zing-bottabot',
            'zumbot',

            // accessed when tweeted
            'ning/1.0',
            'yahoo:linkexpander:slingstone',
            'google-http-java-client/1.17.0-rc (gzip)',
            'js-kit url resolver',
            'htmlparser',
            'paperlibot',

            // xenu
            'xenu link sleuth',
        );

        // Check if the current UA string contains a know bot string
        $is_bot = ( str_replace( $bots, '', $current ) != $current );

        return $is_bot;
    }
    
    /**
     * Disqus HMAC function for authenticated user
     * 
     * @param type $data
     * @param type $key
     * @return type
     */
    public static function dsqHmacsha1($data, $key)
    {
        $blocksize = 64;
        $hashfunc = 'sha1';
        if (strlen($key) > $blocksize){
            $key=pack('H*', $hashfunc($key));
        }
        
        $key=str_pad($key,$blocksize,chr(0x00));
        $ipad=str_repeat(chr(0x36),$blocksize);
        $opad=str_repeat(chr(0x5c),$blocksize);
        
        $hmac = pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$data
                    )
                )
            )
        );
        
        return bin2hex($hmac);
    }
    
    /**
     * Insert something after a specific paragraph in some content.
     *
     * @param  string $insertion    Likely HTML markup, ad script code etc.
     * @param  string $content      Likely HTML markup.
     * @return string               Likely HTML markup.
     */
    function insertAdAfterParagraph($content, $insertion )
    {
        $closing_p = '</p>';
        
        $paragraphs = explode($closing_p, $content);
        
        $length = round(count($paragraphs)/2);
        
        foreach ($paragraphs as $index => $paragraph) {
            
            if (trim($paragraph)) {
                $paragraphs[$index] .= $closing_p;
            }
            if ($length == $index) {
                $paragraphs[$index] .= $insertion;
            }
        }
        return implode('', $paragraphs);
    }

    /**
     *  Get random elements from input array
     * @param array $inputArray
     * @param integer $resultCount Number of elements required in output array
     * @return array Random elements from array
     */
    public static function randomArray($inputArray, $resultCount = 1) 
    {
        shuffle($inputArray);

        $results = array();
        for ($i = 0; $i < $resultCount; $i++) {
            $results[] = $inputArray[$i];
        }
        return $resultCount == 1 ? $results[0] : $results;
    }    
    
    public static function insertInContentHtml($html, $insertHtml, $tag = '</p>', $position = 2)
    {
        $parts = explode($tag, $html);
        $output = '';
        
        $count = count($parts); // call count() only once, it's faster
        
        if ($count > $position) {
            for($i = 1; $i <= $count; $i++) {
                $output .= $parts[$i - 1] . '</p>';
                if ($i == $position) {
                    $output .= $insertHtml;
                }
            }
        }
        else {
            $output = $html;
        }
        
        return $output;
    }
    

    public static function checkIfRemoteFileExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if(curl_exec($ch) !== FALSE) {
            return true; 
        }
        else { 
            return false; 
        }
    }

    
    public static function largeNumberToWords($n) 
    {
        // first strip any formatting;
        $n = (0+str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) return false;

        // now filter it;
        if ($n > 1000000000000) return round(($n/1000000000000), 3).'T';
        elseif ($n > 1000000000) return round(($n/1000000000), 3).'B';
        elseif ($n > 1000000) return round(($n/1000000), 3).'M';
        elseif ($n > 1000) return round(($n/1000), 3).'K';

        return number_format($n);
    }

    public static function GUIDv4 ($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
                  substr($charid,  0,  8).$hyphen.
                  substr($charid,  8,  4).$hyphen.
                  substr($charid, 12,  4).$hyphen.
                  substr($charid, 16,  4).$hyphen.
                  substr($charid, 20, 12).
                  $rbrace;
        return $guidv4;
    }
    
    public static function randomStringToken($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public static function getTimeElapsed($time)
    {
        $elapsedTime = time() - $time;

        if ($elapsedTime < 1) {
            return '0 seconds';
        }

        $timeCalArr = array(365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        
        $timeElapsedPluralArr = array('year' => 'years',
            'month' => 'months',
            'day' => 'days',
            'hour' => 'hr',
            'minute' => 'min',
            'second' => 'sec'
        );

        foreach ($timeCalArr as $secs => $str) {
            $days = $elapsedTime / $secs;
            if ($days >= 1) {
                $roundFigureDays = round($days);
                return $roundFigureDays . ' ' . ($roundFigureDays > 1 ? $timeElapsedPluralArr[$str] : $str) . ' ago';
            }
        }
    }
    
    /**
     * Removes https, http and an ending / from given url 
     * @param string $fullUrl
     * @return displayable URL
     */
    public static function beautifyUrlToDisplay($fullUrl)
    {
        if(empty($fullUrl)) {
            return FALSE;
        }
        
        $modUrl = preg_replace('#^https?://#', '', $fullUrl);
        
        return rtrim($modUrl, '/');
    }
    
    /**
     * Creates a hash key from provided guid and a salt
     * @param string $guid
     * @return string hash-value
     */
    public static function generateHashKey($guid)
    {
        $salt = \Yii::$app->params['hash.salt'];
        
        if(empty($salt) || empty($guid)) {
            return FALSE;
        }
        
        return hash_hmac('sha256', $guid, $salt);
    }
    
    /**
     * Verifies a hash-key, whether it is same before request & after response
     * @param string $guid
     * @param string $hashKey
     * @return boolean valid hash key TRUE|FALSE
     */
    public static function verifyHashKey($guid, $hashKey)
    {
        $salt = \Yii::$app->params['hash.salt'];
        
        if(empty($salt) || empty($guid)) {
            return FALSE;
        }
        
        $genHaskKey = hash_hmac('sha256', $guid, $salt);
        return hash_equals($genHaskKey, $hashKey);
    }
    
    /**
     * Returns true or false
     * indicating whether or not the URL
     * passes a TLD check.
     */
    public static function checkTLD($url) 
    {
        $parsed_url = parse_url($url);
        
        if ( $parsed_url === FALSE ) {
            return false;
        }
        
        return preg_match('/\.(aero|asia|biz|cat|com|coop|info|int|jobs|mobi|museum|name|net|org|post|pro|tel|travel|mlcee|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bl|bm|bn|bo|bq|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cw|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mf|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|za|zm|zw)$/i', $parsed_url['host']);
    }
    
    /**
     * Check provided domain is valid
     * @param type $domain_name
     * @return type
     */
    public static function isValidDomainName($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }

    /**
     * Returns list of top level domains.
     */
    public static function getTopLevelDomains() 
    {
      return array(
        'aero'   => 'air-transport industry',
        'asia'   => 'Asia-Pacific region',
        'biz'    => 'business',
        'cat'    => 'Catalan',
        'com'    => 'commercial',
        'coop'   => 'cooperatives',
        'info'   => 'information',
        'int'    => 'international organizations',
        'jobs'   => 'companies',
        'mobi'   => 'mobile devices',
        'museum' => 'museums',
        'name'   => 'individuals, by name',
        'net'    => 'network',
        'org'    => 'organization',
        'post'   => 'postal services',
        'pro'    => 'professions',
        'tel'    => 'Internet communication services',
        'travel' => 'travel and tourism industry related sites',
/*          
        'xxx'    => 'Porn',

        'ac' => "Ascension Island",
        'ad' => "Andorra",
        'ae' => "United Arab Emirates",
        'af' => "Afghanistan",
        'ag' => "Antigua and Barbuda",
        'ai' => "Anguilla",
        'al' => "Albania",
        'am' => "Armenia",
        'an' => "Netherlands Antilles (being phased out)",
        'ao' => "Angola",
        'aq' => "Antarctica",
        'ar' => "Argentina",
        'as' => "American Samoa",
        'at' => "Austria",
        'au' => "Australia",
        'aw' => "Aruba",
        'ax' => "Aland Islands",
        'az' => "Azerbaijan",
        'ba' => "Bosnia and Herzegovina",
        'bb' => "Barbados",
        'bd' => "Bangladesh",
        'be' => "Belgium",
        'bf' => "Burkina Faso",
        'bg' => "Bulgaria",
        'bh' => "Bahrain",
        'bi' => "Burundi",
        'bj' => "Benin",
        'bl' => "Saint Barthelemy",
        'bm' => "Bermuda",
        'bn' => "Brunei Darussalam",
        'bo' => "Bolivia",
        'bq' => "Bonaire, Sint Eustatius and Saba",
        'br' => "Brazil",
        'bs' => "Bahamas",
        'bt' => "Bhutan",
        'bv' => "Bouvet Island",
        'bw' => "Botswana",
        'by' => "Belarus",
        'bz' => "Belize",
        'ca' => "Canada",
        'cc' => "Cocos (Keeling) Islands",
        'cd' => "Congo, The Democratic Republic of the",
        'cf' => "Central African Republic",
        'cg' => "Congo",
        'ch' => "Switzerland",
        'ci' => "Cote d'Ivoire",
        'ck' => "Cook Islands",
        'cl' => "Chile",
        'cm' => "Cameroon",
        'cn' => "China",
        'co' => "Colombia",
        'cr' => "Costa Rica",
        'cu' => "Cuba",
        'cv' => "Cape Verde",
        'cw' => "Curaçao",
        'cx' => "Christmas Island",
        'cy' => "Cyprus",
        'cz' => "Czech Republic",
        'de' => "Germany",
        'dj' => "Djibouti",
        'dk' => "Denmark",
        'dm' => "Dominica",
        'do' => "Dominican Republic",
        'dz' => "Algeria",
        'ec' => "Ecuador",
        'ee' => "Estonia",
        'eg' => "Egypt",
        'eh' => "Western Sahara",
        'er' => "Eritrea",
        'es' => "Spain",
        'et' => "Ethiopia",
        'eu' => "European Union",
        'fi' => "Finland",
        'fj' => "Fiji",
        'fk' => "Falkland Islands (Malvinas)",
        'fm' => "Micronesia, Federated States of",
        'fo' => "Faroe Islands",
        'fr' => "France",
        'ga' => "Gabon",
        'gb' => "United Kingdom",
        'gd' => "Grenada",
        'ge' => "Georgia",
        'gf' => "French Guiana",
        'gg' => "Guernsey",
        'gh' => "Ghana",
        'gi' => "Gibraltar",
        'gl' => "Greenland",
        'gm' => "Gambia",
        'gn' => "Guinea",
        'gp' => "Guadeloupe",
        'gq' => "Equatorial Guinea",
        'gr' => "Greece",
        'gs' => "South Georgia and the South Sandwich Islands",
        'gt' => "Guatemala",
        'gu' => "Guam",
        'gw' => "Guinea-Bissau",
        'gy' => "Guyana",
        'hk' => "Hong Kong",
        'hm' => "Heard Island and McDonald Islands",
        'hn' => "Honduras",
        'hr' => "Croatia",
        'ht' => "Haiti",
        'hu' => "Hungary",
        'id' => "Indonesia",
        'ie' => "Ireland",
        'il' => "Israel",
        'im' => "Isle of Man",
        'in' => "India",
        'io' => "British Indian Ocean Territory",
        'iq' => "Iraq",
        'ir' => "Iran, Islamic Republic of",
        'is' => "Iceland",
        'it' => "Italy",
        'je' => "Jersey",
        'jm' => "Jamaica",
        'jo' => "Jordan",
        'jp' => "Japan",
        'ke' => "Kenya",
        'kg' => "Kyrgyzstan",
        'kh' => "Cambodia",
        'ki' => "Kiribati",
        'km' => "Comoros",
        'kn' => "Saint Kitts and Nevis",
        'kp' => "Korea, Democratic People's Republic of",
        'kr' => "Korea, Republic of",
        'kw' => "Kuwait",
        'ky' => "Cayman Islands",
        'kz' => "Kazakhstan",
        'la' => "Lao People's Democratic Republic",
        'lb' => "Lebanon",
        'lc' => "Saint Lucia",
        'li' => "Liechtenstein",
        'lk' => "Sri Lanka",
        'lr' => "Liberia",
        'ls' => "Lesotho",
        'lt' => "Lithuania",
        'lu' => "Luxembourg",
        'lv' => "Latvia",
        'ly' => "Libyan Arab Jamahiriya",
        'ma' => "Morocco",
        'mc' => "Monaco",
        'md' => "Moldova, Republic of",
        'me' => "Montenegro",
        'mf' => "Saint Martin (French part)",
        'mg' => "Madagascar",
        'mh' => "Marshall Islands",
        'mk' => "Macedonia, The Former Yugoslav Republic of",
        'ml' => "Mali",
        'mlc' => "Copycat Easter Egg",
        'mm' => "Myanmar",
        'mn' => "Mongolia",
        'mo' => "Macao",
        'mp' => "Northern Mariana Islands",
        'mq' => "Martinique",
        'mr' => "Mauritania",
        'ms' => "Montserrat",
        'mt' => "Malta",
        'mu' => "Mauritius",
        'mv' => "Maldives",
        'mw' => "Malawi",
        'mx' => "Mexico",
        'my' => "Malaysia",
        'mz' => "Mozambique",
        'na' => "Namibia",
        'nc' => "New Caledonia",
        'ne' => "Niger",
        'nf' => "Norfolk Island",
        'ng' => "Nigeria",
        'ni' => "Nicaragua",
        'nl' => "Netherlands",
        'no' => "Norway",
        'np' => "Nepal",
        'nr' => "Nauru",
        'nu' => "Niue",
        'nz' => "New Zealand",
        'om' => "Oman",
        'pa' => "Panama",
        'pe' => "Peru",
        'pf' => "French Polynesia",
        'pg' => "Papua New Guinea",
        'ph' => "Philippines",
        'pk' => "Pakistan",
        'pl' => "Poland",
        'pm' => "Saint Pierre and Miquelon",
        'pn' => "Pitcairn",
        'pr' => "Puerto Rico",
        'ps' => "Palestinian Territory, Occupied",
        'pt' => "Portugal",
        'pw' => "Palau",
        'py' => "Paraguay",
        'qa' => "Qatar",
        're' => "Reunion",
        'ro' => "Romania",
        'rs' => "Serbia",
        'ru' => "Russian Federation",
        'rw' => "Rwanda",
        'sa' => "Saudi Arabia",
        'sb' => "Solomon Islands",
        'sc' => "Seychelles",
        'sd' => "Sudan",
        'se' => "Sweden",
        'sg' => "Singapore",
        'sh' => "Saint Helena",
        'si' => "Slovenia",
        'sj' => "Svalbard and Jan Mayen",
        'sk' => "Slovakia",
        'sl' => "Sierra Leone",
        'sm' => "San Marino",
        'sn' => "Senegal",
        'so' => "Somalia",
        'sr' => "Suriname",
        'st' => "Sao Tome and Principe",
        'su' => "Soviet Union (being phased out)",
        'sv' => "El Salvador",
        'sx' => "Sint Maarten (Dutch part)",
        'sy' => "Syrian Arab Republic",
        'sz' => "Swaziland",
        'tc' => "Turks and Caicos Islands",
        'td' => "Chad",
        'tf' => "French Southern Territories",
        'tg' => "Togo",
        'th' => "Thailand",
        'tj' => "Tajikistan",
        'tk' => "Tokelau",
        'tl' => "Timor-Leste",
        'tm' => "Turkmenistan",
        'tn' => "Tunisia",
        'to' => "Tonga",
        'tp' => "Portuguese Timor (being phased out)",
        'tr' => "Turkey",
        'tt' => "Trinidad and Tobago",
        'tv' => "Tuvalu",
        'tw' => "Taiwan, Province of China",
        'tz' => "Tanzania, United Republic of",
        'ua' => "Ukraine",
        'ug' => "Uganda",
        'uk' => "United Kingdom",
        'um' => "United States Minor Outlying Islands",
        'us' => "United States",
        'uy' => "Uruguay",
        'uz' => "Uzbekistan",
        'va' => "Holy See (Vatican City State)",
        'vc' => "Saint Vincent and the Grenadines",
        've' => "Venezuela, Bolivarian Republic of",
        'vg' => "Virgin Islands, British",
        'vi' => "Virgin Islands, U.S.",
        'vn' => "Viet Nam",
        'vu' => "Vanuatu",
        'wf' => "Wallis and Futuna",
        'ws' => "Samoa",
        'ye' => "Yemen",
        'yt' => "Mayotte",
        'za' => "South Africa",
        'zm' => "Zambia",
        'zw' => "Zimbabwe",
 * 
 */
        );
    }    
    
    public static function GetCountryISDCodes()
    {
        $countries = [
            'Afghanistan' => '93', 
            'Åland Islands' => '358', 
            'Albania' => '355', 
            'Algeria' => '213', 
            'American Samoa' => '1684', 
            'Andorra' => '376', 
            'Angola' => '244', 
            'Anguilla' => '1264', 
            'Antarctica' => '672', 
            'Antigua and Barbuda' => '1268', 
            'Argentina' => '54', 
            'Armenia' => '374', 
            'Aruba' => '297', 
            'Australia' => '61', 
            'Austria' => '43', 
            'Azerbaijan' => '994', 
            'Bahamas' => '1242', 
            'Bahrain' => '973', 
            'Bangladesh' => '880', 
            'Barbados' => '1246', 
            'Belarus' => '375', 
            'Belgium' => '32', 
            'Belize' => '501', 
            'Benin' => '229', 
            'Bermuda' => '1441', 
            'Bhutan' => '975', 
            'Bolivia' => '591', 
            'Bosnia and Herzegovina' => '387', 
            'Botswana' => '267', 
            'Bouvet Island' => '61', 
            'Brazil' => '55', 
            'British Indian Ocean Territory' => '246', 
            'Brunei Darussalam' => '672', 
            'Bulgaria' => '359', 
            'Burkina Faso' => '226', 
            'Burundi' => '257', 
            'Cambodia' => '855', 
            'Cameroon' => '231', 
            'Canada' => '1', 
            'Cape Verde' => '238', 
            'Cayman Islands' => '1345', 
            'Central African Republic' => '236', 
            'Chad' => '235', 
            'Chile' => '56', 
            'China' => '86', 
            'Christmas Island' => '61', 
            'Cocos (Keeling) Islands' => '891', 
            'Colombia' => '57', 
            'Comoros' => '269', 
            'Congo' => '242', 
            'The Democratic Republic of The Congo' => '243', 
            'Cook Islands' => '682', 
            'Costa Rica' => '506', 
            'Cote Divoire' => '225', 
            'Croatia' => '385', 
            'Cuba' => '53', 
            'Cyprus' => '357', 
            'Czech Republic' => '420', 
            'Denmark' => '45', 
            'Djibouti' => '253', 
            'Dominica' => '1767', 
            'Dominican Republic' => '1809', 
            'Ecuador' => '593', 
            'Egypt' => '20', 
            'El Salvador' => '503', 
            'Equatorial Guinea' => '240', 
            'Eritrea' => '291', 
            'Estonia' => '372', 
            'Ethiopia' => '251', 
            'Falkland Islands (Malvinas)' => '500', 
            'Faroe Islands' => '298', 
            'Fiji' => '679', 
            'Finland' => '238', 
            'France' => '33', 
            'French Guiana' => '594', 
            'French Polynesia' => '689', 
            'French Southern Territories' => '262', 
            'Gabon' => '241', 
            'Gambia' => '220', 
            'Georgia' => '995', 
            'Germany' => '49', 
            'Ghana' => '233', 
            'Gibraltar' => '350', 
            'Greece' => '30', 
            'Greenland' => '299', 
            'Grenada' => '1473', 
            'Guadeloupe' => '590', 
            'Guam' => '1871', 
            'Guatemala' => '502', 
            'Guernsey' => '44', 
            'Guinea' => '224', 
            'Guinea-bissau' => '245', 
            'Guyana' => '592', 
            'Haiti' => '509', 
            'Heard Island and Mcdonald Islands' => '672', 
            'Holy See (Vatican City State)' => '379', 
            'Honduras' => '504', 
            'Hong Kong' => '852', 
            'Hungary' => '36', 
            'Iceland' => '354', 
            'India' => '91', 
            'Indonesia' => '62', 
            'Iran' => '98', 
            'Iraq' => '964', 
            'Ireland' => '353', 
            'Isle of Man' => '44', 
            'Israel' => '972', 
            'Italy' => '39', 
            'Jamaica' => '1876', 
            'Japan' => '81', 
            'Jersey' => '44', 
            'Jordan' => '962', 
            'Kazakhstan' => '7', 
            'Kenya' => '254', 
            'Kiribati' => '686', 
            'Democratic People Republic of Korea' => '850', 
            'Republic of Korea' => '82', 
            'Kuwait' => '965', 
            'Kyrgyzstan' => '996', 
            'Lao People Democratic Republic' => '856', 
            'Latvia' => '371', 
            'Lebanon' => '961', 
            'Lesotho' => '266', 
            'Liberia' => '231', 
            'Libya' => '218', 
            'Liechtenstein' => '423', 
            'Lithuania' => '370', 
            'Luxembourg' => '352', 
            'Macao' => '853', 
            'Macedonia' => '389', 
            'Madagascar' => '261', 
            'Malawi' => '265', 
            'Malaysia' => '60', 
            'Maldives' => '960', 
            'Mali' => '223', 
            'Malta' => '356', 
            'Marshall Islands' => '692', 
            'Martinique' => '596', 
            'Mauritania' => '222', 
            'Mauritius' => '230', 
            'Mayotte' => '262', 
            'Mexico' => '52', 
            'Micronesia' => '691', 
            'Moldova' => '373', 
            'Monaco' => '377', 
            'Mongolia' => '976', 
            'Montenegro' => '382', 
            'Montserrat' => '1664', 
            'Morocco' => '212', 
            'Mozambique' => '258', 
            'Myanmar' => '95', 
            'Namibia' => '264', 
            'Nauru' => '674', 
            'Nepal' => '977', 
            'Netherlands' => '31', 
            'Netherlands Antilles' => '599', 
            'New Caledonia' => '687', 
            'New Zealand' => '64', 
            'Nicaragua' => '505', 
            'Niger' => '227', 
            'Nigeria' => '234', 
            'Niue' => '683', 
            'Norfolk Island' => '672', 
            'Northern Mariana Islands' => '1670', 
            'Norway' => '47', 
            'Oman' => '968', 
            'Pakistan' => '92', 
            'Palau' => '680', 
            'Palestinia' => '970', 
            'Panama' => '507', 
            'Papua New Guinea' => '675', 
            'Paraguay' => '595', 
            'Peru' => '51', 
            'Philippines' => '63', 
            'Pitcairn' => '870', 
            'Poland' => '48', 
            'Portugal' => '351', 
            'Puerto Rico' => '1', 
            'Qatar' => '974', 
            'Reunion' => '262', 
            'Romania' => '40', 
            'Russian Federation' => '7', 
            'Rwanda' => '250', 
            'Saint Helena' => '290', 
            'Saint Kitts and Nevis' => '1869', 
            'Saint Lucia' => '1758', 
            'Saint Pierre and Miquelon' => '508', 
            'Saint Vincent and The Grenadines' => '1784', 
            'Samoa' => '685', 
            'San Marino' => '378', 
            'Sao Tome and Principe' => '239', 
            'Saudi Arabia' => '966', 
            'Senegal' => '221', 
            'Serbia' => '381', 
            'Seychelles' => '248', 
            'Sierra Leone' => '232', 
            'Singapore' => '65', 
            'Slovakia' => '421', 
            'Slovenia' => '386', 
            'Solomon Islands' => '677', 
            'Somalia' => '252', 
            'South Africa' => '27', 
            'South Sudan' => '211', 
            'South Georgia and The South Sandwich Islands' => '500', 
            'Spain' => '34', 
            'Sri Lanka' => '94', 
            'Sudan' => '249', 
            'Suriname' => '597', 
            'Svalbard and Jan Mayen' => '47', 
            'Swaziland' => '268', 
            'Sweden' => '46', 
            'Switzerland' => '41', 
            'Syrian Arab Republic' => '963', 
            'Taiwan, Province of China' => '886', 
            'Tajikistan' => '992', 
            'Tanzania, United Republic of' => '255', 
            'Thailand' => '66', 
            'Timor-leste' => '670', 
            'Togo' => '228', 
            'Tokelau' => '690', 
            'Tonga' => '676', 
            'Trinidad and Tobago' => '1868', 
            'Tunisia' => '216', 
            'Turkey' => '90', 
            'Turkmenistan' => '993', 
            'Turks and Caicos Islands' => '1649', 
            'Tuvalu' => '688', 
            'Uganda' => '256', 
            'Ukraine' => '380', 
            'United Arab Emirates' => '971', 
            'United Kingdom' => '44', 
            'United States' => '1', 
            'United States Minor Outlying Islands' => '1', 
            'Uruguay' => '598', 
            'Uzbekistan' => '998', 
            'Vanuatu' => '678', 
            'Venezuela' => '58', 
            'Vietnam' => '84', 
            'Virgin Islands, British' => '1284', 
            'Virgin Islands, U.S.' => '1430', 
            'Wallis and Futuna' => '681', 
            'Western Sahara' => '212', 
            'Yemen' => '967', 
            'Zambia' => '260', 
            'Zimbabwe' => '263'
        ];
        
        return $countries;
    }
    
    public static function outputJsonResponse($returnArr = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        return $returnArr;
    }
    
    public static function getExcerpt($str, $startPos=0, $maxLength=90)
    {
        if(strlen($str) > $maxLength){
            $excerpt = substr($str, $startPos, $maxLength - 3);
            $lastSpace = strrpos($excerpt, ' ');
            $excerpt = substr($excerpt, 0, $lastSpace);
            $excerpt .= '...';
        }
        else{
            $excerpt = $str;
        }
        return $excerpt;
    }
    
    public static function getEstimatedReadingTime($string)
    {
        $words = str_word_count(strip_tags($string));
        $min = floor($words / 180);
        return ($min == 0) ?  1 : $min;
    }
    
    public static function renderReadingTime($time)
    {
        if ($time <= 59) {
            return $time . ' min read';
        }
        return round($time / 60) . 'hour read';
    }

    public static function convertModelErrorsToString($errors)
    {
        $errorStr = '';
        foreach ($errors as $attribute => $attributeErrors) {
            $errorStr .= implode(", ", $attributeErrors) . ". ";
        }
        
        $errorStr = str_replace(".,", ",", $errorStr);
        $errorStr = str_replace("..", ".", $errorStr);
        
        return rtrim($errorStr, ", ");
    }
    
    public static function createDirectory($dirPath)
    {
        if ($dirPath != '' && !is_dir($dirPath)) {
            if (!\yii\helpers\FileHelper::createDirectory($dirPath)) {
                \Yii::error('Error creating directory to upload: ' . $dirPath);
                throw new \Exception('Error creating directory to upload file.');
            }
        }
    } 
    
    public static function downloadfile($downloadFilePath, $saveFilePath)
    {
        try {

            $ch = curl_init($downloadFilePath);
            if (!$ch) {
                return false;
            }
            $fp = fopen($saveFilePath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            return $saveFilePath;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    
    public static function extractZip($zip_file, $dir_extract) {
        $filesArr = [];     

        $zip = new \ZipArchive();
        $res = $zip->open($zip_file);

        if($res === TRUE) {
            for($i = 0; $i < $zip->numFiles; $i++) {
                $filesArr[] = $dir_extract."/".$zip->getNameIndex($i);
            }

            // extract the files
            $zip->extractTo($dir_extract);
            $zip->close();

            return $filesArr;
        }
        else {
            return FALSE;
        }
    }
    
    /**
     * Function to convert time to different timezone
     * 
     * @param type $dateTime
     * @param type $convertToTimezone
     * @param type $dateType
     * @param type $dateFromTimezone
     * @param type $disableStrToTime
     * @return type
     */
    public static function getTimezoneDateTime($dateTime, $convertToTimezone = 'UTC', $dateType = 'SHORT', $dateFromTimezone = 'UTC', $disableStrToTime = false)
    {
        try {
            $dateTimeString = ctype_digit($dateTime) ? '@' . $dateTime : (!$disableStrToTime ? '@' . strtotime($dateTime) : $dateTime);
            $dateObj = new \DateTime($dateTimeString, new \DateTimeZone($dateFromTimezone));
            $dateObj->setTimezone(new \DateTimeZone($convertToTimezone));
            
            if (is_array($dateType)) {
                $returnArray = [];
                foreach ($dateType as $key => $format) {
                    $returnArray[$key] = $dateObj->format($format);
                }
                
                return $returnArray;
            }
            else {
                switch ($dateType) {
                    CASE 'SHORT':
                        return $dateObj->format('M d Y');
                    CASE 'LONG':
                        return $dateObj->format('F d Y');
                    CASE 'META':
                        return $dateObj->format('Y-m-d');
                    default:
                        return $dateObj->format($dateType);
                }
            }
        }
        catch (\Exception $ex) {
            return $dateTime;
        }
    }
    
    /**
     * Convert a timestamp from $fromTimezone to $toTimezone
     * 
     * @param type $timestamp
     * @param type $format
     * @param type $toTimezone
     * @param type $fromTimezone
     * @return type
     */
    public static function convertNetworkTimeZone($timestamp, $format = 'd-m-Y H:i', $toTimezone = 'UTC', $fromTimezone = 'UTC')
    {
        try {
            return self::getTimezoneDateTime($timestamp, $toTimezone, $format, $fromTimezone);
        }
        catch (\Exception $ex) {
            return $timestamp;
        }
    }
    
    public static function getCurrentTimestamp($networkTimeZone = 'UTC', $returnFormat = 'U', $timestamp = null)
    {
        $dateObj = new \DateTime();
        $dateObj->setTimezone(new \DateTimeZone($networkTimeZone));
        if (!empty($timestamp)) {
            $dateObj->setTimestamp($timestamp);
        }
        return $dateObj->format($returnFormat);
    }
    
    public static function secondsToHHMMSS($seconds, $hideZeroHH = true, $concatHMS = false)
    {
        $H = floor($seconds / 3600);
        $M = ($seconds / 60) % 60;
        $S = $seconds % 60;
        
        if($concatHMS) {
            return ($hideZeroHH && $H) == 0 ? sprintf("%02dm:%02ds", $M, $S) : sprintf("%02dh:%02dm:%02ds", $H, $M, $S);
        }
        
        return ($hideZeroHH && $H) == 0 ? sprintf("%02d:%02d", $M, $S) : sprintf("%02d:%02d:%02d", $H, $M, $S);
        # 02:22:05
    }
    
    // function to convert array to xml
    public static function array_to_xml( $data, &$xml_data, $index = 'item') {
        
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = $index; 
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode, $index);
            } else {
                $value = ($value == '' || $value === NULL) ? 'NULL' : $value;
                if($key == 'content') {
                    $xml_data->addChildWithCData("$key",htmlspecialchars("$value"));
                }
                else{
                    $xml_data->addChild("$key",htmlspecialchars("$value"));
                }                
            }
         }
    }
    
    public static function create_zip($files = array(),$destination = '',$overwrite = false) 
    {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { 
            return false;
        }

        //vars
        $valid_files = array();

        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }

        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new \ZipArchive();
            if($zip->open($destination,$overwrite ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) {
                return false;
            }

            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,'articles.xml');
            }

            //close the zip -- done!
            $zip->close();

            //delete the files
            foreach($valid_files as $file) {
                unlink($file);
            }

            return $destination;
        }
        else
        {
            return false;
        }
    }
    
    public static function isTestingApplication()
    {
        if(defined('YII_ENV') && YII_ENV === "test") { //strpos(strtolower(Yii::$app->id), 'test')
            return TRUE;
        }
        
        return FALSE;
    }
    
    public static function getOpenGraphImageSize($imgWidth, $imgHeight)
    {
        $origImageWidth = $imgWidth;
        $origImageHeight = $imgHeight;

        if ($origImageWidth >= $origImageHeight) {
            //1200 x 630
            if ($imgWidth > 1200) {
                $imgHeight = round($imgHeight * (1200/$imgWidth));
                $imgWidth = 1200;
            }
        }
        else {
            if ($imgHeight > 630) {
                $imgWidth = round($imgWidth * (630/$imgHeight));
                $imgHeight = 630;
            }
        }
        
        $imgWidth = $imgWidth < 200 ? 200 : $imgWidth;
        $imgHeight = $imgHeight < 200 ? 200 : $imgHeight;
        
        return [
            (int)$imgWidth,
            (int)$imgHeight
        ];
    }

    public static function fractionToSeconds($minutesSeconds, $separator = '.')
    {
        if($minutesSeconds <= 0) {
            return $minutesSeconds;
        }
        
        //Get fraction, after decimal value
        $wholeNo = floor($minutesSeconds);
        $fraction = $minutesSeconds - $wholeNo;
        
        if($fraction <= 0) {
            return $minutesSeconds;
        }

        //Convert fraction to seconds
        $seconds = $fraction * 60;
        $roundedSecs = round($seconds);
        
        //less then 10 prepend zero
        $finalSeconds = ($roundedSecs < 10) ? '0' . $roundedSecs : $roundedSecs;
        
        return $wholeNo . $separator . $finalSeconds;
    }
    
    public static function getOffsetSecondsByTimezone($timezone)
    {
        $dtz = new \DateTimeZone($timezone);
        $timeNetwork = new \DateTime('now', $dtz);
        $secondsOffset = $dtz->getOffset($timeNetwork);
//        $hoursOffset = floor($dtz->getOffset($timeNetwork) / 3600);
        //  echo "UTC" . ($offset < 0 ? $offset : "+".$offset);
        
        return $secondsOffset;
    }
    
    /**
     * Get date of a current week day
     * @param string $day
     * @param string $format
     * @param string $timezone
     * @return \DateTime
     */
    public static function getDayByTimezone($day, $format, $timezone = "UTC", $whichWeek = '')
    {
        $day = ucfirst(strtolower($day));
        $days = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];

        $dtz = new \DateTimeZone($timezone);
        $todayNetwork = new \DateTime('now', $dtz);
        if($whichWeek == 'last-week') {
            $todayNetwork->sub(new \DateInterval('P7D'));
        }
        else if($whichWeek == 'next-week') {
            $todayNetwork->add(new \DateInterval('P7D'));
        }
        
        
        $todayNetwork->setISODate((int)$todayNetwork->format('o'), (int)$todayNetwork->format('W'), $days[ucfirst($day)]);
        return $todayNetwork->format($format);
    }

    public static function getUrlPath($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        return ltrim($path, '/');
    }
    
    public static function getImageExtensionByMimeType($imageUrl)
    {
        $image_info = getimagesize($imageUrl);
        $extension = 'jpg';
        
        if (isset($image_info['mime'])) {
            switch ($image_info['mime']) {
                case 'image/gif':
                    $extension = 'gif';
                    break;
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':        
                    $extension = 'png';
                    break;
                default:
                    break;
            }
        }
        
        return $extension;
    }

    public static function getFileExtension($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    public static function getFileMimeType($file)
    {
        // use the standard finfo extension 
        $mime = self::mimeFromFileInfo($file);

        // use the mime_content_type function
        if(!$mime) {
            $mime = self::mimeFromMimeContentType($file);
        }

        if(!$mime) {
            $mime = static::extensionToMime(self::getFileExtension($file));      
        }

        // fix broken mime detection for svg files with style attribute
        if($mime === 'text/html' && self::getFileExtension($file) === 'svg') {
            $svg = new \SimpleXMLElement(static::read($file));
            if($svg !== false && $svg->getName() === 'svg') {
                $mime = 'image/svg+xml';
            }
        }

        return $mime;
    }

    public static function mimeFromFileInfo($file) 
    {
        if(function_exists('finfo_file') && file_exists($file)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file);
            finfo_close($finfo);
            return $mime;
        } 
        
        return false;
    }

      /**
       * Returns the mime type of a file
       *
       * @param string $file
       * @return string|false
       */
    public static function mimeFromMimeContentType($file) 
    {
        if(function_exists('mime_content_type') && file_exists($file) && $mime = @mime_content_type($file) !== false) {
            return $mime;
        } 
        
        return false;
    }

    /**
     * Converts a file extension to a mime type
     *
     * @param string $extension
     * @return string
     */
    public static function extensionToMime($extension) 
    {
        $mimes = array(
            'hqx'   => 'application/mac-binhex40',
            'cpt'   => 'application/mac-compactpro',
            'csv'   => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream'),
            'bin'   => 'application/macbinary',
            'dms'   => 'application/octet-stream',
            'lha'   => 'application/octet-stream',
            'lzh'   => 'application/octet-stream',
            'exe'   => array('application/octet-stream', 'application/x-msdownload'),
            'class' => 'application/octet-stream',
            'psd'   => 'application/x-photoshop',
            'so'    => 'application/octet-stream',
            'sea'   => 'application/octet-stream',
            'dll'   => 'application/octet-stream',
            'oda'   => 'application/oda',
            'pdf'   => array('application/pdf', 'application/x-download'),
            'ai'    => 'application/postscript',
            'eps'   => 'application/postscript',
            'ps'    => 'application/postscript',
            'smi'   => 'application/smil',
            'smil'  => 'application/smil',
            'mif'   => 'application/vnd.mif',
            'wbxml' => 'application/wbxml',
            'wmlc'  => 'application/wmlc',
            'dcr'   => 'application/x-director',
            'dir'   => 'application/x-director',
            'dxr'   => 'application/x-director',
            'dvi'   => 'application/x-dvi',
            'gtar'  => 'application/x-gtar',
            'gz'    => 'application/x-gzip',
            'php'   => array('text/php', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source'),
            'php3'  => array('text/php', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source'),
            'phtml' => array('text/php', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source'),
            'phps'  => array('text/php', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source'),
            'js'    => 'application/x-javascript',
            'swf'   => 'application/x-shockwave-flash',
            'sit'   => 'application/x-stuffit',
            'tar'   => 'application/x-tar',
            'tgz'   => array('application/x-tar', 'application/x-gzip-compressed'),
            'xhtml' => 'application/xhtml+xml',
            'xht'   => 'application/xhtml+xml',
            'zip'   => array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
            'mid'   => 'audio/midi',
            'midi'  => 'audio/midi',
            'mpga'  => 'audio/mpeg',
            'mp2'   => 'audio/mpeg',
            'mp3'   => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
            'm4a'   => 'audio/mp4',    
            'aif'   => 'audio/x-aiff',
            'aiff'  => 'audio/x-aiff',
            'aifc'  => 'audio/x-aiff',
            'ram'   => 'audio/x-pn-realaudio',
            'rm'    => 'audio/x-pn-realaudio',
            'rpm'   => 'audio/x-pn-realaudio-plugin',
            'ra'    => 'audio/x-realaudio',
            'rv'    => 'video/vnd.rn-realvideo',
            'wav'   => 'audio/x-wav',
            'bmp'   => 'image/bmp',
            'gif'   => 'image/gif',
            'ico'   => 'image/x-icon',
            'jpg'   => array('image/jpeg', 'image/pjpeg'),
            'jpeg'  => array('image/jpeg', 'image/pjpeg'),
            'jpe'   => array('image/jpeg', 'image/pjpeg'),
            'png'   => 'image/png',
            'tiff'  => 'image/tiff',
            'tif'   => 'image/tiff',
            'svg'   => 'image/svg+xml',
            'css'   => 'text/css',
            'html'  => 'text/html',
            'htm'   => 'text/html',
            'shtml' => 'text/html',
            'txt'   => 'text/plain',
            'text'  => 'text/plain',
            'log'   => array('text/plain', 'text/x-log'),
            'rtx'   => 'text/richtext',
            'ics'   => 'text/calendar',
            'rtf'   => 'text/rtf',
            'xml'   => 'text/xml',
            'xsl'   => 'text/xml',
            'mpeg'  => 'video/mpeg',
            'mpg'   => 'video/mpeg',
            'mpe'   => 'video/mpeg',
            'mp4'   => 'video/mp4',
            'm4v'   => 'video/mp4',
            'qt'    => 'video/quicktime',
            'mov'   => 'video/quicktime',
            'avi'   => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'doc'   => 'application/msword',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dotx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'xls'   => array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xltx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'ppt'   => array('application/powerpoint', 'application/vnd.ms-powerpoint'),
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'potx'  => 'application/vnd.openxmlformats-officedocument.presentationml.template',
            'word'  => array('application/msword', 'application/octet-stream'),
            'xl'    => 'application/excel',
            'eml'   => 'message/rfc822',
            'json'  => array('application/json', 'text/json'),
            'odt'   => 'application/vnd.oasis.opendocument.text',
            'odc'   => 'application/vnd.oasis.opendocument.chart',
            'odp'   => 'application/vnd.oasis.opendocument.presentation',
            'webm'  => 'video/webm'
        );

        $mime = isset($mimes[$extension]) ? $mimes[$extension] : null;

        return is_array($mime) ? array_shift($mime) : $mime;
    }

    public static function getRemoteFileExtension($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_exec($ch);
        $mimeType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);        
        $mime = current(explode(';',$mimeType));
        
        return self::getFileExt($mime);
    }
    
    public static function getFileExt($contentType)
    {
        $map = array(
            'application/pdf'   => 'pdf',
            'application/zip'   => 'zip',
            'image/gif'         => 'gif',
            'image/jpeg'        => 'jpg',
            'image/png'         => 'png',
            'text/css'          => 'css',
            'text/html'         => 'html',
            'text/javascript'   => 'js',
            'text/plain'        => 'txt',
            'text/xml'          => 'xml',
            'application/msword' => 'doc',
            'image/x-photoshop' => 'psd',
            'image/tiff' => 'tif',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/msexcel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/mspowerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'video/x-flv' => 'flv',
            'video/mp4' => 'mp4',
            'video/3gpp' => '3gp',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'video/x-ms-wmv' => 'wmv'
        );
        
        if (isset($map[$contentType]))
        {
            return $map[$contentType];
        }

        $pieces = explode('/', $contentType);
        return array_pop($pieces);
    }
    
    public static function getFileTypeFromMimeType($mimeType)
    {
        if(strstr($mimeType, "video/")){
            $fileType = 'video';
        }
        elseif(strstr($mimeType, "image/")){
            $fileType = 'image';
        }
        else {
            $fileType = 'doc';
        }
        
        return $fileType;
    }
    
    public static function GetAbsoluteDomain($url)
    {
        $urlobj = parse_url($url);
        $domain = $urlobj['host'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
          return $regs['domain'];
        }
        
        return false;
    }
    
    public static function getRelativeUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        return (!empty($path)) ? $path : '/';
    }
    
    public static function findArrayValuesRecursive($key, array $arr )
    {
        $val = [];
        array_walk_recursive($arr, function($v, $k) use($key, &$val) {
            if ($v == $key) {
                array_push($val, $v);
            }
                
            
        });
        return count($val) > 1 ? $val : array_pop($val);
    }
    
    public static function cleanGuid( $guid, $validation = FALSE)
    {
        if($guid === NULL) return $guid;
        
        $guid = str_replace(' ', '', $guid); // Replaces all spaces with no spaces.
        $guid = preg_replace('/[^A-Za-z0-9\-]/', '', $guid); // Removes special chars.

        if ($validation) {
            // match Guid Format 00000000(8char)-0000 (4char) - 0000 (4char) - 0000 (4char)-000000000000 (12char)
            if (preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $guid)) {
                return $guid;
            }
            return false;
        }

        return $guid;
    }
    
    public static function calculateDateDiff($startTimestamp, $endTimestamp)
    {
        $diff = abs($endTimestamp - $startTimestamp);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor($diff/3600);
        return [
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'hours' => $hours
        ];
    }
    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        }
        else {
            $bytes = 0;
        }

        return $bytes;
    }

    public static function  getProtectedValue($obj, $name)
    {
        $array = (array) $obj;
        $prefix = chr(0) . '*' . chr(0);
        return $array[$prefix . $name];
    }
    
    public static function getAllWeekOrMonthDaysFromToday($currentTimestamp, $endTimestamp)
    {
        $timeleft = $currentTimestamp - $endTimestamp;
        $daysleft = abs(round((($timeleft / 24) / 60) / 60));

        $days = [];
        $day = 60 * 60 * 24;
        for ($i = 0; $i < $daysleft; $i++) {
            $ts = $currentTimestamp + $day * $i;
            $days[$ts] = date("l d M Y", $ts);
        }
        return $days;
    }

    public static function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = [];
        foreach ($arr as $key=> $row) {
            if (!isset($row[$col])) return $arr;
            $sort_col[$key] = $row[$col];
        }

        return array_multisort($sort_col, $dir, $arr);
    }
    
    public static function isValidYear($year) 
    {
        // Convert to timestamp
        $start_year         =   strtotime(date('Y') - 10); //10 Years back
        $end_year           =   strtotime(date('Y') + 10); // 10 Years forward
        $received_year      =   strtotime($year);

       // Check that year is between start & end
       return (($received_year >= $start_year) && ($received_year <= $end_year));
    }
    
    public static function calculateHeightWidth($targetWidth, & $imgArr)
    {
        $aspectRatio = (!empty($imgArr['height']) && $imgArr['height'] > 0 ? $imgArr['height'] : 300) / (!empty($imgArr['width']) && $imgArr['width'] > 0 ? $imgArr['width'] : 300);
        $targetHeight = (int) ($aspectRatio * $targetWidth);
        $imgArr['width'] = $targetWidth;
        $imgArr['height'] = $targetHeight;
    }

    public static function arrayChunks($list, $totalChunks)
    {
        $listlen = count($list);
        $partlen = floor($listlen / $totalChunks);
        $partrem = $listlen % $totalChunks;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $totalChunks; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }
        
        return $partition;
    }
    
    public static function getOGTags($url)
    {
        $html = file_get_contents($url);

        @libxml_use_internal_errors(true);
        $dom = new \DomDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $result = $xpath->query($query);
        
        $list = [];
        foreach ($result as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');

            // replace og
            $property = str_replace('og:', '', $property);
            $list[$property] = $content;
        }
        
        $tags = get_meta_tags($url);
        
        if(!isset($list['title'])) {
            $list['title'] = isset($tags['title']) ? $tags['title'] : (isset($tags['description']) ? $tags['description'] : '');
        }
        
        if(!isset($list['description'])) {
            $list['description'] = isset($tags['description']) ? $tags['description'] : (isset($tags['title']) ? $tags['title'] : '');
        }
        
        if(!isset($list['image'])) {
            $list['image'] = "https://www.placehold.it/1600x1000/EFEFEF/AAAAAA&text=no+image";
        }
        
        if(!isset($list['url'])) {
            $list['url'] = $url;
        }
            
        return $list;
    }

    public static function buildCollectionList($collection)
    {
        $collections = \common\models\AssetCollection::findByParentId($collection['id'], ['resultCount' => 'all']);
        $isParent = (count($collections) > 0) ? 1 : 0;
        $li = "<li class='".(!$isParent ? 'no-arrow' : '' )."'><span class='icon collapsed'></span><div class='heirarchy-grid__container'>{$collection['name']}<div class='option-sets'><span class='fa fa-edit editCollection' data-guid='{$collection['guid']}'></span><span class='fa fa-trash deleteCollection' data-is-parent='{$isParent}' data-guid='{$collection['guid']}'></span></div></div>";  
        if(count($collections) > 0) {
            $li .= "<ul>";        
            foreach($collections as $collectionModel) {             
                $li .= self::buildCollectionList($collectionModel);                
            }
            $li .= "</ul>";
        }
        $li .= "</li>";
        
        return $li;
    } 
    
    public static function buildNetworkCollectionList($collection, $type)
    {
        $collections = \common\models\NetworkCollection::findByParentId($collection['id'], ['type' => $type, 'resultCount' => 'all']);
        $isParent = (count($collections) > 0) ? 1 : 0;
        $li = "<li class='".(!$isParent ? 'no-arrow' : '')."'><span class='icon collapsed'></span><div class='heirarchy-grid__container'>{$collection['name']}<div class='option-sets'><span class='fa fa-edit editCollection' data-guid='{$collection['guid']}' data-type='{$type}'></span><span class='fa fa-trash deleteCollection' data-is-parent='{$isParent}' data-guid='{$collection['guid']}' data-type='{$type}'></span></div></div>";  
        if(count($collections) > 0) {
            $li .= "<ul>";        
            foreach($collections as $collectionModel) {                
                $li .= self::buildNetworkCollectionList($collectionModel, $type);                
            }
            $li .= "</ul>";
        }
        $li .= "</li>";
        
        return $li;
    }
    
    public static function makeLinks($str, $params = [])
    {
        $reg_exUrl = "~[a-z]+://\S+~i";
        //$reg_exUrl = "#\b[a-zA-Z]+://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#";
        $urls = array();
        $urlsToReplace = array();
        if (preg_match_all($reg_exUrl, $str, $urls)) {
            $numOfMatches = count($urls[0]);
            $numOfUrlsToReplace = 0;
            for ($i = 0; $i < $numOfMatches; $i++) {
                $alreadyAdded = false;
                $numOfUrlsToReplace = count($urlsToReplace);
                for ($j = 0; $j < $numOfUrlsToReplace; $j++) {
                    if ($urlsToReplace[$j] == $urls[0][$i]) {
                        $alreadyAdded = true;
                    }
                }
                if (!$alreadyAdded) {
                    array_push($urlsToReplace, $urls[0][$i]);
                }
            }
            $numOfUrlsToReplace = count($urlsToReplace);
            for ($i = 0; $i < $numOfUrlsToReplace; $i++) {
                $str = str_replace($urlsToReplace[$i], "<a class='link-blue' target='_blank' href=\"" . $urlsToReplace[$i] . "\">" . $urlsToReplace[$i] . "</a> ", $str);
            }
            return $str;
        }
        else {
            return $str;
        }
    }
    
    public static function extractHrefFromHtml($html, $defaultLink = NULL)
    {
        try {
            preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $html, $result);
            if (!empty($result)) {
                return $result['href'][0];
            }
        }
        catch(\Exception $e) {} 
        
        return $defaultLink;
    }
    
    public static function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    
    public static function convertMinutes($seconds)
    {
        $days = floor($seconds / (24 * 60 * 60));
        $hours = floor(($seconds - ($days * 24 * 60 * 60)) / (60 * 60));
        $minutes = floor(($seconds - ($days * 24 * 60 * 60) - ($hours * 60 * 60)) / 60);
        

        $output = '';
        if (!empty($days) && $days > 0 ) {
            $output .= $days . 'd ';
        }
        if (!empty($hours)  && $hours > 0 ) {
            $output .= $hours . 'h ';
        }
        if (!empty($minutes)  && $minutes > 0 ) {
            $output .= $minutes . 'm';
        }
        
        return !empty($output) ? $output : '0m';
    }

    /**
     * Convert time-string to seconds
     * @param string $timeStr eg 2d 20h 35m
     */
    public static function convertDHMtimeToSeconds($timeStr)
    {
        $totalEstimated = 0;
        $timeStrFormatted = self::formatDHM($timeStr);
        if(!empty($timeStrFormatted)) {
            $timeArr = explode(" ", $timeStrFormatted);
            foreach ($timeArr as $time) {
                $totalEstimated += self::convertDHMToSeconds($time);
            }
        }
        return $totalEstimated;
    }
    
    public static function convertDHMToSeconds($timeStr)
    {
        $totalEstimated = 0;
        $time = strtolower($timeStr);
        if (strpos($time, 'd') !== FALSE) {
            $totalEstimated += ((int) substr($time, 0, -1)) * 86400;
        }
        if (strpos($time, 'h') !== FALSE) {
            $totalEstimated += ((int) substr($time, 0, -1)) * 3600;
        }
        if (strpos($time, 'm') !== FALSE) {
            $totalEstimated += (int) substr($time, 0, -1) * 60;
        }
        return $totalEstimated;
    }
    
    public static function formatDHM($timeStr)
    {
        $str = "";
        try {
            $pattern = "/([0-9]+[d|h|m|D|H|M]+)/";
            $subPattern = "/([0-9]+|[a-zA-Z]+)/";
            preg_match_all($pattern, $timeStr, $arr);
            if(!empty($arr[0])) {
                $days = $hours = $minutes = "";
                foreach($arr[0] as $tstr) {
                    preg_match_all($subPattern, $tstr, $arr2);
                    if(!empty($arr2[0]) && isset($arr2[0][0]) && isset($arr2[0][1]) && $arr2[0][0]>0) {
                        switch(strtolower($arr2[0][1])) {
                            case 'd':
                                $days = $arr2[0][0] . strtolower($arr2[0][1]);
                                break;
                            case 'h':
                                $hours = $arr2[0][0] . strtolower($arr2[0][1]);
                                break;
                            case 'm':
                                $minutes = $arr2[0][0] . strtolower($arr2[0][1]);
                                break;
                        }
                    }
                }

                $str = $days;
                if(!empty($hours)) {
                    $str .= (empty($str)) ? $hours : ' ' . $hours;
                }
                if(!empty($minutes)) {
                    $str .= (empty($str)) ? $minutes : ' ' . $minutes;
                }
            }
        }
        catch(\Exception $ex) {}
        
        return $str;
    }
    
    /**
     * @param type $content
     * @param type $br
     * @return refrence https://wordpress.stackexchange.com/questions/7090/stop-wordpress-wrapping-images-in-a-p-tag
     */
    public static function wordpressContentBuilder($content, $br = 1)
    {

        if (trim($content) === '') {
            return '';
        }

        $content = $content . "\n";
        $content = preg_replace('|<br />\s*<br />|', "\n\n", $content);

         $content = preg_replace(
'/\[caption[\s]{0,}(.*?)\].*?(<img.*?\/?>).*?\[\/caption\]/ims', 
'<figure $1> $2 </figure>',
$content); 
        
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li
|pre|select|option|form|map|area|blockquote|img|address|math|style|input
|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer
|nav|figure|figcaption|details|menu|summary)';

        $content = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $content);
        $content = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $content);
        $content = str_replace(array("\r\n", "\r"), "\n", $content);

        if (strpos($content, '<object') !== false) {
            $content = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $content);
            $content = preg_replace('|\s*</embed>\s*|', '</embed>', $content);
        }

        $content = preg_replace("/\n\n+/", "\n\n", $content);
        $contents = preg_split('/\n\s*\n/', $content, -1, PREG_SPLIT_NO_EMPTY);

        $content = '';
        foreach ($contents as $tinkle) {
            $content .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }

        $content = preg_replace('|<p>\s*</p>|', '', $content);
        $content = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $content);
        $content = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $content);
        $content = preg_replace("|<p>(<li.+?)</p>|", "$1", $content); // problem with nested lists
        $content = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $content);
        $content = str_replace('</blockquote></p>', '</p></blockquote>', $content);
        $content = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $content);
        $content = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $content);

        if ($br) {
            $content = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $content);
            $content = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $content);
            $content = str_replace('<WPPreserveNewline />', "\n", $content);
        }

        $content = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $content);
        $content = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $content);
        if (strpos($content, '<pre') !== false) {
            $content = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', function($matches) {
                if (is_array($matches)) {
                    $text = $matches[1] . $matches[2] . "</pre>";
                }
                else {
                    $text = $matches;
                }
                $text = str_replace(array('<br />', '<br/>', '<br>'), array('', '', ''), $text);
                $text = str_replace('<p>', "\n", $text);
                $text = str_replace('</p>', '', $text);

                return $text;
            }, $content);
        }

        $content = preg_replace("|\n</p>$|", '</p>', $content);

        return $content;
    }
    
    public static function removeSchema($url)
    {
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }

    public static function getFontLibraryArr()
    {
        return [
            "fa fa-address-book",
            "fa fa-address-book-o",
            "fa fa-address-card",
            "fa fa-address-card-o",
            "fa fa-adjust",
            "fa fa-adn",
            "fa fa-align-center",
            "fa fa-align-justify",
            "fa fa-align-left",
            "fa fa-align-right",
            "fa fa-amazon",
            "fa fa-ambulance",
            "fa fa-american-sign-language-interpreting",
            "fa fa-anchor",
            "fa fa-android",
            "fa fa-angellist",
            "fa fa-angle-double-down",
            "fa fa-angle-double-left",
            "fa fa-angle-double-right",
            "fa fa-angle-double-up",
            "fa fa-angle-down",
            "fa fa-angle-left",
            "fa fa-angle-right",
            "fa fa-angle-up",
            "fa fa-apple",
            "fa fa-archive",
            "fa fa-area-chart",
            "fa fa-arrow-circle-down",
            "fa fa-arrow-circle-left",
            "fa fa-arrow-circle-o-down",
            "fa fa-arrow-circle-o-left",
            "fa fa-arrow-circle-o-right",
            "fa fa-arrow-circle-o-up",
            "fa fa-arrow-circle-right",
            "fa fa-arrow-circle-up",
            "fa fa-arrow-down",
            "fa fa-arrow-left",
            "fa fa-arrow-right",
            "fa fa-arrow-up",
            "fa fa-arrows",
            "fa fa-arrows-alt",
            "fa fa-arrows-h",
            "fa fa-arrows-v",
            "fa fa-asl-interpreting",
            "fa fa-assistive-listening-systems",
            "fa fa-asterisk",
            "fa fa-at",
            "fa fa-audio-description",
            "fa fa-automobile",
            "fa fa-backward",
            "fa fa-balance-scale",
            "fa fa-ban",
            "fa fa-bandcamp",
            "fa fa-bank",
            "fa fa-bar-chart",
            "fa fa-bar-chart-o",
            "fa fa-barcode",
            "fa fa-bars",
            "fa fa-bath",
            "fa fa-bathtub",
            "fa fa-battery",
            "fa fa-battery-0",
            "fa fa-battery-1",
            "fa fa-battery-2",
            "fa fa-battery-3",
            "fa fa-battery-4",
            "fa fa-battery-empty",
            "fa fa-battery-full",
            "fa fa-battery-half",
            "fa fa-battery-quarter",
            "fa fa-battery-three-quarters",
            "fa fa-bed",
            "fa fa-beer",
            "fa fa-behance",
            "fa fa-behance-square",
            "fa fa-bell",
            "fa fa-bell-o",
            "fa fa-bell-slash",
            "fa fa-bell-slash-o",
            "fa fa-bicycle",
            "fa fa-binoculars",
            "fa fa-birthday-cake",
            "fa fa-bitbucket",
            "fa fa-bitbucket-square",
            "fa fa-bitcoin",
            "fa fa-black-tie",
            "fa fa-blind",
            "fa fa-bluetooth",
            "fa fa-bluetooth-b",
            "fa fa-bold",
            "fa fa-bolt",
            "fa fa-bomb",
            "fa fa-book",
            "fa fa-bookmark",
            "fa fa-bookmark-o",
            "fa fa-braille",
            "fa fa-briefcase",
            "fa fa-btc",
            "fa fa-bug",
            "fa fa-building",
            "fa fa-building-o",
            "fa fa-bullhorn",
            "fa fa-bullseye",
            "fa fa-bus",
            "fa fa-buysellads",
            "fa fa-cab",
            "fa fa-calculator",
            "fa fa-calendar",
            "fa fa-calendar-check-o",
            "fa fa-calendar-minus-o",
            "fa fa-calendar-o",
            "fa fa-calendar-plus-o",
            "fa fa-calendar-times-o",
            "fa fa-camera",
            "fa fa-camera-retro",
            "fa fa-car",
            "fa fa-caret-down",
            "fa fa-caret-left",
            "fa fa-caret-right",
            "fa fa-caret-square-o-down",
            "fa fa-caret-square-o-left",
            "fa fa-caret-square-o-right",
            "fa fa-caret-square-o-up",
            "fa fa-caret-up",
            "fa fa-cart-arrow-down",
            "fa fa-cart-plus",
            "fa fa-cc",
            "fa fa-cc-amex",
            "fa fa-cc-diners-club",
            "fa fa-cc-discover",
            "fa fa-cc-jcb",
            "fa fa-cc-mastercard",
            "fa fa-cc-paypal",
            "fa fa-cc-stripe",
            "fa fa-cc-visa",
            "fa fa-certificate",
            "fa fa-chain",
            "fa fa-chain-broken",
            "fa fa-check",
            "fa fa-check-circle",
            "fa fa-check-circle-o",
            "fa fa-check-square",
            "fa fa-check-square-o",
            "fa fa-chevron-circle-down",
            "fa fa-chevron-circle-left",
            "fa fa-chevron-circle-right",
            "fa fa-chevron-circle-up",
            "fa fa-chevron-down",
            "fa fa-chevron-left",
            "fa fa-chevron-right",
            "fa fa-chevron-up",
            "fa fa-child",
            "fa fa-chrome",
            "fa fa-circle",
            "fa fa-circle-o",
            "fa fa-circle-o-notch",
            "fa fa-circle-thin",
            "fa fa-clipboard",
            "fa fa-clock-o",
            "fa fa-clone",
            "fa fa-close",
            "fa fa-cloud",
            "fa fa-cloud-download",
            "fa fa-cloud-upload",
            "fa fa-cny",
            "fa fa-code",
            "fa fa-code-fork",
            "fa fa-codepen",
            "fa fa-codiepie",
            "fa fa-coffee",
            "fa fa-cog",
            "fa fa-cogs",
            "fa fa-columns",
            "fa fa-comment",
            "fa fa-comment-o",
            "fa fa-commenting",
            "fa fa-commenting-o",
            "fa fa-comments",
            "fa fa-comments-o",
            "fa fa-compass",
            "fa fa-compress",
            "fa fa-connectdevelop",
            "fa fa-contao",
            "fa fa-copy",
            "fa fa-copyright",
            "fa fa-creative-commons",
            "fa fa-credit-card",
            "fa fa-credit-card-alt",
            "fa fa-crop",
            "fa fa-crosshairs",
            "fa fa-css3",
            "fa fa-cube",
            "fa fa-cubes",
            "fa fa-cut",
            "fa fa-cutlery",
            "fa fa-dashboard",
            "fa fa-dashcube",
            "fa fa-database",
            "fa fa-deaf",
            "fa fa-deafness",
            "fa fa-dedent",
            "fa fa-delicious",
            "fa fa-desktop",
            "fa fa-deviantart",
            "fa fa-diamond",
            "fa fa-digg",
            "fa fa-dollar",
            "fa fa-dot-circle-o",
            "fa fa-download",
            "fa fa-dribbble",
            "fa fa-drivers-license",
            "fa fa-drivers-license-o",
            "fa fa-dropbox",
            "fa fa-drupal",
            "fa fa-edge",
            "fa fa-edit",
            "fa fa-eercast",
            "fa fa-eject",
            "fa fa-ellipsis-h",
            "fa fa-ellipsis-v",
            "fa fa-empire",
            "fa fa-envelope",
            "fa fa-envelope-o",
            "fa fa-envelope-open",
            "fa fa-envelope-open-o",
            "fa fa-envelope-square",
            "fa fa-envira",
            "fa fa-eraser",
            "fa fa-etsy",
            "fa fa-eur",
            "fa fa-euro",
            "fa fa-exchange",
            "fa fa-exclamation",
            "fa fa-exclamation-circle",
            "fa fa-exclamation-triangle",
            "fa fa-expand",
            "fa fa-expeditedssl",
            "fa fa-external-link",
            "fa fa-external-link-square",
            "fa fa-eye",
            "fa fa-eye-slash",
            "fa fa-eyedropper",
            "fa fa-fa",
            "fa fa-facebook",
            "fa fa-facebook-f",
            "fa fa-facebook-official",
            "fa fa-facebook-square",
            "fa fa-fast-backward",
            "fa fa-fast-forward",
            "fa fa-fax",
            "fa fa-feed",
            "fa fa-female",
            "fa fa-fighter-jet",
            "fa fa-file",
            "fa fa-file-archive-o",
            "fa fa-file-audio-o",
            "fa fa-file-code-o",
            "fa fa-file-excel-o",
            "fa fa-file-image-o",
            "fa fa-file-movie-o",
            "fa fa-file-o",
            "fa fa-file-pdf-o",
            "fa fa-file-photo-o",
            "fa fa-file-picture-o",
            "fa fa-file-powerpoint-o",
            "fa fa-file-sound-o",
            "fa fa-file-text",
            "fa fa-file-text-o",
            "fa fa-file-video-o",
            "fa fa-file-word-o",
            "fa fa-file-zip-o",
            "fa fa-files-o",
            "fa fa-film",
            "fa fa-filter",
            "fa fa-fire",
            "fa fa-fire-extinguisher",
            "fa fa-firefox",
            "fa fa-first-order",
            "fa fa-flag",
            "fa fa-flag-checkered",
            "fa fa-flag-o",
            "fa fa-flash",
            "fa fa-flask",
            "fa fa-flickr",
            "fa fa-floppy-o",
            "fa fa-folder",
            "fa fa-folder-o",
            "fa fa-folder-open",
            "fa fa-folder-open-o",
            "fa fa-font",
            "fa fa-font-awesome",
            "fa fa-fonticons",
            "fa fa-fort-awesome",
            "fa fa-forumbee",
            "fa fa-forward",
            "fa fa-foursquare",
            "fa fa-free-code-camp",
            "fa fa-frown-o",
            "fa fa-futbol-o",
            "fa fa-gamepad",
            "fa fa-gavel",
            "fa fa-gbp",
            "fa fa-ge",
            "fa fa-gear",
            "fa fa-gears",
            "fa fa-genderless",
            "fa fa-get-pocket",
            "fa fa-gg",
            "fa fa-gg-circle",
            "fa fa-gift",
            "fa fa-git",
            "fa fa-git-square",
            "fa fa-github",
            "fa fa-github-alt",
            "fa fa-github-square",
            "fa fa-gitlab",
            "fa fa-gittip",
            "fa fa-glass",
            "fa fa-glide",
            "fa fa-glide-g",
            "fa fa-globe",
            "fa fa-google",
            "fa fa-google-plus",
            "fa fa-google-plus-circle",
            "fa fa-google-plus-official",
            "fa fa-google-plus-square",
            "fa fa-google-wallet",
            "fa fa-graduation-cap",
            "fa fa-gratipay",
            "fa fa-grav",
            "fa fa-group",
            "fa fa-h-square",
            "fa fa-hacker-news",
            "fa fa-hand-grab-o",
            "fa fa-hand-lizard-o",
            "fa fa-hand-o-down",
            "fa fa-hand-o-left",
            "fa fa-hand-o-right",
            "fa fa-hand-o-up",
            "fa fa-hand-paper-o",
            "fa fa-hand-peace-o",
            "fa fa-hand-pointer-o",
            "fa fa-hand-rock-o",
            "fa fa-hand-scissors-o",
            "fa fa-hand-spock-o",
            "fa fa-hand-stop-o",
            "fa fa-handshake-o",
            "fa fa-hard-of-hearing",
            "fa fa-hashtag",
            "fa fa-hdd-o",
            "fa fa-header",
            "fa fa-headphones",
            "fa fa-heart",
            "fa fa-heart-o",
            "fa fa-heartbeat",
            "fa fa-history",
            "fa fa-home",
            "fa fa-hospital-o",
            "fa fa-hotel",
            "fa fa-hourglass",
            "fa fa-hourglass-1",
            "fa fa-hourglass-2",
            "fa fa-hourglass-3",
            "fa fa-hourglass-end",
            "fa fa-hourglass-half",
            "fa fa-hourglass-o",
            "fa fa-hourglass-start",
            "fa fa-houzz",
            "fa fa-html5",
            "fa fa-i-cursor",
            "fa fa-id-badge",
            "fa fa-id-card",
            "fa fa-id-card-o",
            "fa fa-ils",
            "fa fa-image",
            "fa fa-imdb",
            "fa fa-inbox",
            "fa fa-indent",
            "fa fa-industry",
            "fa fa-info",
            "fa fa-info-circle",
            "fa fa-inr",
            "fa fa-instagram",
            "fa fa-institution",
            "fa fa-internet-explorer",
            "fa fa-intersex",
            "fa fa-ioxhost",
            "fa fa-italic",
            "fa fa-joomla",
            "fa fa-jpy",
            "fa fa-jsfiddle",
            "fa fa-key",
            "fa fa-keyboard-o",
            "fa fa-krw",
            "fa fa-language",
            "fa fa-laptop",
            "fa fa-lastfm",
            "fa fa-lastfm-square",
            "fa fa-leaf",
            "fa fa-leanpub",
            "fa fa-legal",
            "fa fa-lemon-o",
            "fa fa-level-down",
            "fa fa-level-up",
            "fa fa-life-bouy",
            "fa fa-life-buoy",
            "fa fa-life-ring",
            "fa fa-life-saver",
            "fa fa-lightbulb-o",
            "fa fa-line-chart",
            "fa fa-link",
            "fa fa-linkedin",
            "fa fa-linkedin-square",
            "fa fa-linode",
            "fa fa-linux",
            "fa fa-list",
            "fa fa-list-alt",
            "fa fa-list-ol",
            "fa fa-list-ul",
            "fa fa-location-arrow",
            "fa fa-lock",
            "fa fa-long-arrow-down",
            "fa fa-long-arrow-left",
            "fa fa-long-arrow-right",
            "fa fa-long-arrow-up",
            "fa fa-low-vision",
            "fa fa-magic",
            "fa fa-magnet",
            "fa fa-mail-forward",
            "fa fa-mail-reply",
            "fa fa-mail-reply-all",
            "fa fa-male",
            "fa fa-map",
            "fa fa-map-marker",
            "fa fa-map-o",
            "fa fa-map-pin",
            "fa fa-map-signs",
            "fa fa-mars",
            "fa fa-mars-double",
            "fa fa-mars-stroke",
            "fa fa-mars-stroke-h",
            "fa fa-mars-stroke-v",
            "fa fa-maxcdn",
            "fa fa-meanpath",
            "fa fa-medium",
            "fa fa-medkit",
            "fa fa-meetup",
            "fa fa-meh-o",
            "fa fa-mercury",
            "fa fa-microchip",
            "fa fa-microphone",
            "fa fa-microphone-slash",
            "fa fa-minus",
            "fa fa-minus-circle",
            "fa fa-minus-square",
            "fa fa-minus-square-o",
            "fa fa-mixcloud",
            "fa fa-mobile",
            "fa fa-mobile-phone",
            "fa fa-modx",
            "fa fa-money",
            "fa fa-moon-o",
            "fa fa-mortar-board",
            "fa fa-motorcycle",
            "fa fa-mouse-pointer",
            "fa fa-music",
            "fa fa-navicon",
            "fa fa-neuter",
            "fa fa-newspaper-o",
            "fa fa-object-group",
            "fa fa-object-ungroup",
            "fa fa-odnoklassniki",
            "fa fa-odnoklassniki-square",
            "fa fa-opencart",
            "fa fa-openid",
            "fa fa-opera",
            "fa fa-optin-monster",
            "fa fa-outdent",
            "fa fa-pagelines",
            "fa fa-paint-brush",
            "fa fa-paper-plane",
            "fa fa-paper-plane-o",
            "fa fa-paperclip",
            "fa fa-paragraph",
            "fa fa-paste",
            "fa fa-pause",
            "fa fa-pause-circle",
            "fa fa-pause-circle-o",
            "fa fa-paw",
            "fa fa-paypal",
            "fa fa-pencil",
            "fa fa-pencil-square",
            "fa fa-pencil-square-o",
            "fa fa-percent",
            "fa fa-phone",
            "fa fa-phone-square",
            "fa fa-photo",
            "fa fa-picture-o",
            "fa fa-pie-chart",
            "fa fa-pied-piper",
            "fa fa-pied-piper-alt",
            "fa fa-pied-piper-pp",
            "fa fa-pinterest",
            "fa fa-pinterest-p",
            "fa fa-pinterest-square",
            "fa fa-plane",
            "fa fa-play",
            "fa fa-play-circle",
            "fa fa-play-circle-o",
            "fa fa-plug",
            "fa fa-plus",
            "fa fa-plus-circle",
            "fa fa-plus-square",
            "fa fa-plus-square-o",
            "fa fa-podcast",
            "fa fa-power-off",
            "fa fa-print",
            "fa fa-product-hunt",
            "fa fa-puzzle-piece",
            "fa fa-qq",
            "fa fa-qrcode",
            "fa fa-question",
            "fa fa-question-circle",
            "fa fa-question-circle-o",
            "fa fa-quora",
            "fa fa-quote-left",
            "fa fa-quote-right",
            "fa fa-ra",
            "fa fa-random",
            "fa fa-ravelry",
            "fa fa-rebel",
            "fa fa-recycle",
            "fa fa-reddit",
            "fa fa-reddit-alien",
            "fa fa-reddit-square",
            "fa fa-refresh",
            "fa fa-registered",
            "fa fa-remove",
            "fa fa-renren",
            "fa fa-reorder",
            "fa fa-repeat",
            "fa fa-reply",
            "fa fa-reply-all",
            "fa fa-resistance",
            "fa fa-retweet",
            "fa fa-rmb",
            "fa fa-road",
            "fa fa-rocket",
            "fa fa-rotate-left",
            "fa fa-rotate-right",
            "fa fa-rouble",
            "fa fa-rss",
            "fa fa-rss-square",
            "fa fa-rub",
            "fa fa-ruble",
            "fa fa-rupee",
            "fa fa-s15",
            "fa fa-safari",
            "fa fa-save",
            "fa fa-scissors",
            "fa fa-scribd",
            "fa fa-search",
            "fa fa-search-minus",
            "fa fa-search-plus",
            "fa fa-sellsy",
            "fa fa-send",
            "fa fa-send-o",
            "fa fa-server",
            "fa fa-share",
            "fa fa-share-alt",
            "fa fa-share-alt-square",
            "fa fa-share-square",
            "fa fa-share-square-o",
            "fa fa-shekel",
            "fa fa-sheqel",
            "fa fa-shield",
            "fa fa-ship",
            "fa fa-shirtsinbulk",
            "fa fa-shopping-bag",
            "fa fa-shopping-basket",
            "fa fa-shopping-cart",
            "fa fa-shower",
            "fa fa-sign-in",
            "fa fa-sign-language",
            "fa fa-sign-out",
            "fa fa-signal",
            "fa fa-signing",
            "fa fa-simplybuilt",
            "fa fa-sitemap",
            "fa fa-skyatlas",
            "fa fa-skype",
            "fa fa-slack",
            "fa fa-sliders",
            "fa fa-slideshare",
            "fa fa-smile-o",
            "fa fa-snapchat",
            "fa fa-snapchat-ghost",
            "fa fa-snapchat-square",
            "fa fa-snowflake-o",
            "fa fa-soccer-ball-o",
            "fa fa-sort",
            "fa fa-sort-alpha-asc",
            "fa fa-sort-alpha-desc",
            "fa fa-sort-amount-asc",
            "fa fa-sort-amount-desc",
            "fa fa-sort-asc",
            "fa fa-sort-desc",
            "fa fa-sort-down",
            "fa fa-sort-numeric-asc",
            "fa fa-sort-numeric-desc",
            "fa fa-sort-up",
            "fa fa-soundcloud",
            "fa fa-space-shuttle",
            "fa fa-spinner",
            "fa fa-spoon",
            "fa fa-spotify",
            "fa fa-square",
            "fa fa-square-o",
            "fa fa-stack-exchange",
            "fa fa-stack-overflow",
            "fa fa-star",
            "fa fa-star-half",
            "fa fa-star-half-empty",
            "fa fa-star-half-full",
            "fa fa-star-half-o",
            "fa fa-star-o",
            "fa fa-steam",
            "fa fa-steam-square",
            "fa fa-step-backward",
            "fa fa-step-forward",
            "fa fa-stethoscope",
            "fa fa-sticky-note",
            "fa fa-sticky-note-o",
            "fa fa-stop",
            "fa fa-stop-circle",
            "fa fa-stop-circle-o",
            "fa fa-street-view",
            "fa fa-strikethrough",
            "fa fa-stumbleupon",
            "fa fa-stumbleupon-circle",
            "fa fa-subscript",
            "fa fa-subway",
            "fa fa-suitcase",
            "fa fa-sun-o",
            "fa fa-superpowers",
            "fa fa-superscript",
            "fa fa-support",
            "fa fa-table",
            "fa fa-tablet",
            "fa fa-tachometer",
            "fa fa-tag",
            "fa fa-tags",
            "fa fa-tasks",
            "fa fa-taxi",
            "fa fa-telegram",
            "fa fa-television",
            "fa fa-tencent-weibo",
            "fa fa-terminal",
            "fa fa-text-height",
            "fa fa-text-width",
            "fa fa-th",
            "fa fa-th-large",
            "fa fa-th-list",
            "fa fa-themeisle",
            "fa fa-thermometer",
            "fa fa-thermometer-0",
            "fa fa-thermometer-1",
            "fa fa-thermometer-2",
            "fa fa-thermometer-3",
            "fa fa-thermometer-4",
            "fa fa-thermometer-empty",
            "fa fa-thermometer-full",
            "fa fa-thermometer-half",
            "fa fa-thermometer-quarter",
            "fa fa-thermometer-three-quarters",
            "fa fa-thumb-tack",
            "fa fa-thumbs-down",
            "fa fa-thumbs-o-down",
            "fa fa-thumbs-o-up",
            "fa fa-thumbs-up",
            "fa fa-ticket",
            "fa fa-times",
            "fa fa-times-circle",
            "fa fa-times-circle-o",
            "fa fa-times-rectangle",
            "fa fa-times-rectangle-o",
            "fa fa-tint",
            "fa fa-toggle-down",
            "fa fa-toggle-left",
            "fa fa-toggle-off",
            "fa fa-toggle-on",
            "fa fa-toggle-right",
            "fa fa-toggle-up",
            "fa fa-trademark",
            "fa fa-train",
            "fa fa-transgender",
            "fa fa-transgender-alt",
            "fa fa-trash",
            "fa fa-trash-o",
            "fa fa-tree",
            "fa fa-trello",
            "fa fa-tripadvisor",
            "fa fa-trophy",
            "fa fa-truck",
            "fa fa-try",
            "fa fa-tty",
            "fa fa-tumblr",
            "fa fa-tumblr-square",
            "fa fa-turkish-lira",
            "fa fa-tv",
            "fa fa-twitch",
            "fa fa-twitter",
            "fa fa-twitter-square",
            "fa fa-umbrella",
            "fa fa-underline",
            "fa fa-undo",
            "fa fa-universal-access",
            "fa fa-university",
            "fa fa-unlink",
            "fa fa-unlock",
            "fa fa-unlock-alt",
            "fa fa-unsorted",
            "fa fa-upload",
            "fa fa-usb",
            "fa fa-usd",
            "fa fa-user",
            "fa fa-user-circle",
            "fa fa-user-circle-o",
            "fa fa-user-md",
            "fa fa-user-o",
            "fa fa-user-plus",
            "fa fa-user-secret",
            "fa fa-user-times",
            "fa fa-users",
            "fa fa-vcard",
            "fa fa-vcard-o",
            "fa fa-venus",
            "fa fa-venus-double",
            "fa fa-venus-mars",
            "fa fa-viacoin",
            "fa fa-viadeo",
            "fa fa-viadeo-square",
            "fa fa-video-camera",
            "fa fa-vimeo",
            "fa fa-vimeo-square",
            "fa fa-vine",
            "fa fa-vk",
            "fa fa-volume-control-phone",
            "fa fa-volume-down",
            "fa fa-volume-off",
            "fa fa-volume-up",
            "fa fa-warning",
            "fa fa-wechat",
            "fa fa-weibo",
            "fa fa-weixin",
            "fa fa-whatsapp",
            "fa fa-wheelchair",
            "fa fa-wheelchair-alt",
            "fa fa-wifi",
            "fa fa-wikipedia-w",
            "fa fa-window-close",
            "fa fa-window-close-o",
            "fa fa-window-maximize",
            "fa fa-window-minimize",
            "fa fa-window-restore",
            "fa fa-windows",
            "fa fa-won",
            "fa fa-wordpress",
            "fa fa-wpbeginner",
            "fa fa-wpexplorer",
            "fa fa-wpforms",
            "fa fa-wrench",
            "fa fa-xing",
            "fa fa-xing-square",
            "fa fa-y-combinator",
            "fa fa-y-combinator-square",
            "fa fa-yahoo",
            "fa fa-yc",
            "fa fa-yc-square",
            "fa fa-yelp",
            "fa fa-yen",
            "fa fa-yoast",
            "fa fa-youtube",
            "fa fa-youtube-play",
            "fa fa-youtube-square",
        ];
	}
	
    public static function validateUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($response == 200) ? TRUE : FALSE;
    }

    public static function getWeekDays($date, $networkTimezone)
    {
        //Starting day of week
        $dayStart = self::getTimezoneDateTime($date, $networkTimezone, 'D', $networkTimezone, true);
        
        //if selected day is not monday then calculate last monday from provided date
        if($dayStart !== "Mon") {
            $startDate = self::getTimezoneDateTime("last monday $date", $networkTimezone, 'U', $networkTimezone, true);
        }
        else { 
            $startDate = self::getTimezoneDateTime($date, $networkTimezone, 'U', $networkTimezone, true);
        }
        
        $endDate = self::getTimezoneDateTime("next sunday $date", $networkTimezone, 'U', $networkTimezone, true);
        
        $dates = [];
        $isWeek = true;

        $start = $startDate;
        while ($isWeek) {
            $dates[] = self::getTimezoneDateTime($start, $networkTimezone, 'Y-m-d', $networkTimezone);
            if($start == $endDate) {
                break;
            }

            $start = $start + 86400;
        }

        return $dates;
    }
    
    public static function dateRange($first, $last, $step = '+1 day', $output_format = 'd/m/Y' )
    {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }
    
    public static function get_email_from_rfc_email($rfc_email_string)
    {
        // extract parts between the two parentheses
        $mailAddress = preg_match('/(?:<)(.+)(?:>)$/', $rfc_email_string, $matches);
        return $matches[1];
    }
    
    //https://github.com/pixelandtonic/AppleNews/blob/master/applenews/helpers/AppleNewsHelper.php
    public static function html2AppleNewsComponents($html, $properties = [])
    {
        // Create a DOMDocument object for the HTML
        // (from HtmlConverter::convert)
        $document = new \DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        $document->encoding = 'UTF-8';
        libxml_clear_errors();
        if (!($body = $document->getElementsByTagName('body')->item(0))) {
            throw new \InvalidArgumentException('Invalid HTML was provided');
        }
        // Create an array of all the top-level elements
        $componentInfos = [];
        $lastWasBody = false;
        foreach ($body->childNodes as $node) {
            /** @var \DOMNode $node */
            // <pre><code> => <code>
            if ($node->nodeName == 'pre' && $node->childNodes->length == 1 && $node->firstChild->nodeName == 'code') {
                $node = $node->firstChild;
            }
            switch ($node->nodeName) {
                case 'blockquote': {
                        $role = 'quote';
                        $types = ['quote'];
                        $isBody = false;
                        $captureText = true;
                        $captureOuterHtml = false;
                        break;
                    }
                case 'code': {
                        $role = 'body';
                        $types = ['code', 'body'];
                        $isBody = false;
                        $captureText = true;
                        $captureOuterHtml = false;
                        break;
                    }
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                case 'h6': {
                        $level = substr($node->nodeName, 1);
                        $role = 'heading' . $level;
                        $types = ['heading' . $level, 'heading'];
                        $isBody = false;
                        $captureText = true;
                        $captureOuterHtml = false;
                        break;
                    }
                case 'hr': {
                        $role = 'divider';
                        $types = ['divider'];
                        $isBody = false;
                        $captureText = false;
                        $captureOuterHtml = false;
                        break;
                    }
                default: {
                        // div, p, ol, ul, #text, etc.
                        $role = 'body';
                        $types = ['body'];
                        $isBody = true;
                        $captureText = true;
                        $captureOuterHtml = true;
                    }
            }
            // Do we care about the text value?
            if ($captureText) {
                // Is the node type important?
                if ($captureOuterHtml) {
                    $nodeHtml = $document->saveHTML($node);
                }
                else {
                    $nodeHtml = '';
                    foreach ($node->childNodes as $childNode) {
                        $nodeHtml .= $document->saveHTML($childNode);
                    }
                }
                $nodeHtml = trim($nodeHtml, "\n\r ");
                if (!$nodeHtml) {
                    continue;
                }
                $text = static::html2Markdown($nodeHtml);
            }
            else {
                $text = null;
            }
            // If this is a body component and the last one was as well, append the text to the last one
            if ($isBody && $lastWasBody) {
                $lastComponentIndex = count($componentInfos) - 1;
                $componentInfos[$lastComponentIndex]['text'] .= "\n\n" . $text;
            }
            else {
                $componentInfos[] = [
                    'types' => $types,
                    'role' => $role,
                    'text' => $text,
                ];
                $lastWasBody = $isBody;
            }
        }
        $components = [];
        foreach ($componentInfos as $componentInfo) {
            $component = null;
            if (is_callable($properties)) {
                // See if the function cares about any of these types
                foreach ($componentInfo['types'] as $type) {
                    $component = $properties($type, $componentInfo['text']);
                    if ($component) {
                        break;
                    }
                }
            }
            if (!$component) {
                $component = [
                    'role' => $componentInfo['role'],
                ];
                if ($componentInfo['text']) {
                    $component['text'] = $componentInfo['text'];
                    $component['format'] = 'markdown';
                }
                if (is_array($properties)) {
                    // Merge in the first matching property key, if any
                    foreach ($componentInfo['types'] as $type) {
                        if (isset($properties[$type])) {
                            $component = array_merge($component, $properties[$type]);
                            break;
                        }
                    }
                }
            }
            $components[] = $component;
        }
        return $components;
    }
    
     public static function html2Markdown($html)
    {
 
        // Trim unwanted whitespace within within block tags
        $blockTags = 'h1|h2|h3|h4|h5|h6|p|ul|ol|li|div';
        $html = preg_replace("/(<(?:{$blockTags}).*?>)\s*/is", "$1", $html);
        $html = trim($html, chr(0xC2).chr(0xA0));
        $md = static::getHtmlConverter()->convert($html);
        return $md;
    }
    
    public static function isValidJson($data = NULL)
    {
        if (!empty($data)) {
            @json_decode($data);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
    
    public static function cleanString($string)
    { 
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = str_replace(['[\', \']'], '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/[^A-Za-z0-9\. -@]/', '', $string);
        return strtolower(trim($string, '-'));
    }

    public static function convertToCamelcase($textToConvert, $seperator = '_')
    {
        return lcfirst(
            implode(
                '',
                array_map(
                    function ($key) {
                        return ucfirst($key);
                    },
                    explode($seperator, trim($textToConvert))
                )
            )
        );
    }
    
    public static function convertArrayKeysToCamelcase($array,$seperator = '_')
    {
        $response = [];
        if ($array != NULL && is_array($array)) {
            foreach ($array as $attribute => $attributeValue) {
                    $response[ self::convertToCamelcase($attribute,$seperator) ] = $attributeValue;
            }
            return $response;
        }
        return NULL;
    }
    
    public static function httpGet($url)
    {
        $headers = array();

        $headers[] = 'Content-Type: text/plain';

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);


        curl_close($curl);

        return $response;
    }
    
    public static function passwordRegex($minlength = 8, $smallCharac = 'a-z', $minsmallCharaLen = 1, $bigCharac = 'A-Z', $minbigCharaLen = 1, $number = '\d', $minNumberLen = 1, $specialChara = '\W', $specialCharaLen = 1)
    {
        $startRegex = '/^\S*';
        $minimunLength = '(?=\S{' . $minlength . '})';
        $smallCharacter = '(?=\S*[' . $smallCharac . ']{' . $minsmallCharaLen . '})';
        $bigCharacter = '(?=\S*[' . $bigCharac . ']{' . $minbigCharaLen . '})';
        $number = '(?=\S*[' . $number . ']{' . $minNumberLen . '})';
        $specialCharacter = '(?=\S*[' . $specialChara . ']{' . $specialCharaLen . '})';
        $endRegex = '\S*$/';

        $finalRegex = $startRegex . $minimunLength . $smallCharacter . $bigCharacter . $number . $specialCharacter . $endRegex;
        return $finalRegex;
    }
    
    public static function displayDate($date)
    {
        return !empty($date) ? date('d-m-Y', strtotime($date)) : '';
    }
    
    public static function alphabetRegex()
    {
        return '/^[a-zA-Z ]*$/';
    }
    
    public static function alphanumericWithSpecialRegex()
    {
        return "/^[a-zA-Z0-9.,!?:;'\/\-\/\s]+$/";
    }
    
    public static function emailConversion($email)
    {
        if(empty($email)){
            return false;
        }
        $string = '';
        $string = str_replace('@', '[at]', $email);
        $string = str_replace('.', '[dot]', $string);
        return $string;
    }
    
    public static function stepsUrl($path, $qr)
    {
        $params = [];
        foreach ($qr as $key => $value) {
            if (!empty($value)) {
                $params[$key] = $value;
            }
        }

        return \yii\helpers\ArrayHelper::merge([0 => $path], $params);
    }
    
    public static function encryptString($string)
    {
        $key = Yii::$app->params['hashKey'];
        return base64_encode($string);
    }
      
    public static function decryptString($string) 
    {
        $key = Yii::$app->params['hashKey'];
        return base64_decode($string);
    }
    
    public static function calculateAge($params = [], $ad = NULL)
    {   
        if(!isset($params['classifiedId']) || !isset($params['applicantPostId'])) {
            return false;
        }
        
        if ($ad == NULL) {
            $ad = \common\models\ApplicantDetail::findByApplicantPostId($params['applicantPostId']);
        }

        $mstPostAge = \common\models\MstPostAge::getPostAge($params, $ad);
        $mstClassified = \common\models\MstClassified::findById($params['classifiedId']);
        $birth_date = new \DateTime($ad['date_of_birth'] . "T00:00:00");
        $current_date = new \DateTime($mstClassified['reference_date']);

        $diff = $birth_date->diff($current_date);
        if ($diff->y >= $mstPostAge['min_age'] && ($diff->y < $mstPostAge['max_age'] || ($diff->y == $mstPostAge['max_age'] && ($diff->m == 0 && $diff->d == 0)))) {
            return true;
        }

        return false;
    }
    
    /**
     * ^ represents the starting of the string.
     * [2-9]{1} represents the first digit should be any from 2-9.
     * [0-9]{3} represents the next 3 digits after the first digit should be any digit from 0-9.
     * \\s represents white space.
     * [0-9]{4} represents the next 4 digits should be any from 0-9.
     * \\s represents white space.
     * [0-9]{4} represents the next 4 digits should be any from 0-9.
     * $ represents the ending of the string.
     * 3675 9834 6012
     * @return string
     */
    public static function aadharRegex()
    {
        return "/^[2-9]\d{11}$/";
        return "/^[2-9]{1}[0-9]{3}\\s[0-9]{4}\\s[0-9]{4}$/";
    }
    
    /**
     * [A-Z]{5} represents the first five upper case alphabets which can be A to Z.
     * [0-9]{4} represents the four numbers which can be 0-9.
     * [A-Z]{1} represents the one upper case alphabet which can be A to Z.
     * BNZAA2318J
     * @return string
     */
    public static function panRegex()
    {
        return "/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/";
    }
    
    /**
     * ^ represents the starting of the string.
     * [2-9]{1} represents the first digit should be any from 2-9.
     * [0-9]{3} represents the next 3 digits after the first digit should be any digit from 0-9.
     * \\s represents white space.
     * [0-9]{4} represents the next 4 digits should be any from 0-9.
     * \\s represents white space.
     * [0-9]{4} represents the next 4 digits should be any from 0-9.
     * $ represents the ending of the string.
     * HR06 19850034761
     * @return string
     */
    public static function drivingLicenseRegex()
    {
        return "/^(([A-Z]{2}[0-9]{2})( ))((19|20)[0-9][0-9])[0-9]{7}$/";
    }
    
    /**
     * ^ represents the starting of the string.
     * [A-PR-WYa-pr-wy] represents the string should be starts with A-Z excluding Q, X, and Z.
     * [1-9] represents the second character should be any number from 1-9.
     * \\d represents the third character should be any number from 0-9.
     * \\s? represents the string should be zero or one white space character.
     * \\d{4} represents the next four characters should be any number from 0-9.
     * [1-9] represents the last character should be any number from 1-9.
     * $ represents the ending of the string.
     * A209645704
     * @return string
     */
    public static function passportRegex()
    {
        return "/^[A-Z]{1}[0-9]{7}$/";
    }
    
    /**
     * 
     * @return string
     */
    public static function voterIdRegex()
    {
        return "/^([a-zA-Z]){3}([0-9]){7}$/";
    }
    
    public static function calculateYear($date, $ageCalculateDate)
    {
        return date_diff(date_create($date), date_create($ageCalculateDate))->y;
    }
    
    public static function calculateMonth($date, $ageCalculateDate)
    {
        return date_diff(date_create($date), date_create($ageCalculateDate))->m;
    }
    
    public static function calculateDay($date, $ageCalculateDate)
    {
        return date_diff(date_create($date), date_create($ageCalculateDate))->d;
    }
    
    public static function displayAge($from, $to)
    {
        return self::calculateYear($from, $to) . ' Years, ' . self::calculateMonth($from, $to) . ' Months, ' . self::calculateDay($from, $to) . ' Days';
    }
    
    public static function checkCscConnect()
    {
        if (!\Yii::$app->params['enable.csc']) {
            return false;
        }
        if (!\Yii::$app->session->has('_connectData')) {
            return false;
        }
        if (empty(\Yii::$app->session->get('_connectData'))) {
            return false;
        }
        return true;
    }

    public static function clean($string)
    { 
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = ltrim($string, '-');        
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}