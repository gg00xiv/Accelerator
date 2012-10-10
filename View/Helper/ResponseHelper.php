<?php

namespace Accelerator\View\Helper;

/**
 * Description of ResponseHelper
 *
 * @author gg00xiv
 */
abstract class ResponseHelper {

    /**
     * Send to the client a 404 error response.
     * 
     */
    public static function notFound($exit = true) {
        header('HTTP/1.0 404 Not Found');
        !$exit or exit;
    }

}

?>
