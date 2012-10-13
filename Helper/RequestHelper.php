<?php

namespace Accelerator\Helper;

/**
 * Description of RequestHelper
 *
 * @author gg00xiv
 */
abstract class RequestHelper {

    private static $userAgent;

    public static function isMobile() {
        if (!self::$userAgent) {
            self::$userAgent = self::getUserAgent();
        }
        return self::$userAgent["type"] == "Mobile";
    }

    public static function getUserAgent($ua_string = null) {
        if (!$ua_string)
            $ua_string = $_SERVER["HTTP_USER_AGENT"];
        // BOTS
        if (stristr($ua_string, "google"))
            $ua = array("type" => "Bot", "name" => "Google Bot");
        else if (stristr($ua_string, "baiduspider"))
            $ua = array("type" => "Bot", "name" => "Baidu (China) Bot");
        else if (stristr($ua_string, "bing"))
            $ua = array("type" => "Bot", "name" => "Bing Bot");
        else if (stristr($ua_string, "sogou"))
            $ua = array("type" => "Bot", "name" => "Sogou (China) Bot");
        else if (stristr($ua_string, "yandex"))
            $ua = array("type" => "Bot", "name" => "Yandex Bot");
        else if (stristr($ua_string, "exabot"))
            $ua = array("type" => "Bot", "name" => "Exa Bot");
        else if (stristr($ua_string, "yandex"))
            $ua = array("type" => "Bot", "name" => "Yandex Bot");
        else if (stristr($ua_string, "nutch"))
            $ua = array("type" => "Bot", "name" => "Nutch Bot");
        else if (stristr($ua_string, "ahrefsbot"))
            $ua = array("type" => "Bot", "name" => "Ahrefs Bot");
        else if (stristr($ua_string, "alexa"))
            $ua = array("type" => "Bot", "name" => "Alexa Bot");
        else if (stristr($ua_string, "ezooms"))
            $ua = array("type" => "Bot", "name" => "eZooms Bot");
        else if (stristr($ua_string, "sistrix crawler"))
            $ua = array("type" => "Bot", "name" => "SISTRIX Crawler");
        else if (stristr($ua_string, "sosospider"))
            $ua = array("type" => "Bot", "name" => "Sosospider");
        else if (stristr($ua_string, "proximic"))
            $ua = array("type" => "Bot", "name" => "Proximic");
        else if (stristr($ua_string, "bot"))
            $ua = array("type" => "Bot", "name" => "A Bot");
        else if (stristr($ua_string, "crawler"))
            $ua = array("type" => "Bot", "name" => "A Crawler");
        else if (stristr($ua_string, "spider"))
            $ua = array("type" => "Bot", "name" => "A Spider");

        // BROWSERS
        else if (stristr($ua_string, "ipad"))
            $ua = array("type" => "Browser", "name" => "iPad");
        else if (stristr($ua_string, "iphone"))
            $ua = array("type" => "Mobile", "name" => "iPhone");
        else if (stristr($ua_string, "android"))
            $ua = array("type" => "Mobile", "name" => "Android");
        else if (stristr($ua_string, "mobile"))
            $ua = array("type" => "Mobile", "name" => "Mobile");
        else if (stristr($ua_string, "msie 2.0"))
            $ua = array("type" => "Browser", "name" => "Internet Explorer 2");
        else if (stristr($ua_string, "msie 5.5"))
            $ua = array("type" => "Browser", "name" => "Internet Explorer 5.5");
        else if (stristr($ua_string, "msie 6.0"))
            $ua = array("type" => "Browser", "name" => "Internet Explorer 6");
        else if (stristr($ua_string, "msie 7.0"))
            $ua = array("type" => "Browser", "name" => "Internet Explorer 7");
        else if (stristr($ua_string, "msie 8.0"))
            $ua = array("type" => "Browser", "name" => "Internet Explorer 8");
        else if (stristr($ua_string, "msie 9.0"))
            $ua = array("type" => "Browser", "name" => "Internet Explorer 9");
        else if (stristr($ua_string, "chrome"))
            $ua = array("type" => "Browser", "name" => "Chrome");
        else if (stristr($ua_string, "opera"))
            $ua = array("type" => "Browser", "name" => "Opera");
        else if (stristr($ua_string, "firefox"))
            $ua = array("type" => "Browser", "name" => "Firefox");
        else if (stristr($ua_string, "macintosh"))
            $ua = array("type" => "Browser", "name" => "Safari");
        else if (stristr($ua_string, "dolfin"))
            $ua = array("type" => "Browser", "name" => "Dolfin");

        return !isset($ua) ? NULL : $ua;
    }

}

?>
