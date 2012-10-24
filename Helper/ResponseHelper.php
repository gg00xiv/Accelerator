<?php

namespace Accelerator\Helper;

/**
 * Description of ResponseHelper
 *
 * @author gg00xiv
 */
abstract class ResponseHelper {

    /**
     * Send to the client a 404 error response.
     * 
     * @param $exit If =True, terminate current request execution.
     */
    public static function notFound($exit = true) {
        header('HTTP/1.0 404 Not Found');
        !$exit or exit;
    }

    /**
     * Redirect current request to another page.
     * 
     * @param type $url Redirection url.
     * @param $exit If =True, terminate current request execution.
     */
    public static function redirect($url, $exit = true) {
        header('Location: ' . $url);
        !$exit or exit;
    }

    public static function setHeader($name, $value) {
        header($name . ': ' . $value);
    }

    public static function setContentType($mimeType, $encoding = null) {
        self::setHeader('Content-Type', $mimeType . ($encoding !== null ? '; charset=' . $encoding : ''));
    }

    public static function setContentTypeXml($encoding = null) {
        self::setContentType('text/xml', $encoding);
    }
    
    public static function setContentTypePng(){
        self::setContentType('image/png');
    }

    public static function setContentTypeText($encoding = null) {
        self::setContentType('text', $encoding);
    }

}

?>
