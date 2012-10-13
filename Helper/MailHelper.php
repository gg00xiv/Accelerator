<?php

namespace Accelerator\Helper;

use Accelerator\AcceleratorException;

/**
 * Description of MailHelper
 *
 * @author gg00xiv
 */
abstract class MailHelper {

    public static function sendHtml($to, $fromName, $fromAddress, $subject, $html, $encoding = 'utf-8') {
        if (!$to || !$fromName || !$fromAddress || !$subject || !$html)
            throw new AcceleratorException('Invalid mail parameter.');

        $headers = 'From: "' . $fromName . '" <' . $fromAddress . '>' . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=$encoding\n";

        return mail($to, $subject, $html, $headers);
    }

    public static function sendText($to, $subject, $message) {
        return mail($to, $subject, $message);
    }

}

?>
