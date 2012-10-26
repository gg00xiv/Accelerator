<?php

namespace Accelerator\Helper;

/**
 * Description of MailHelper
 *
 * @author gg00xiv
 */
abstract class MailHelper {

    /**
     * Send an HTML formatted email.
     * 
     * @param string $to
     * @param string $fromName
     * @param string $fromAddress
     * @param string $subject
     * @param string $html
     * @param string $encoding
     * @return boolean mail function return.
     * @throws \Accelerator\Exception\ArgumentNullException
     */
    public static function sendHtml($to, $fromName, $fromAddress, $subject, $html, $encoding = 'utf-8') {
        if (!$to)
            throw new \Accelerator\Exception\ArgumentNullException('$to');
        if (!$fromName)
            throw new \Accelerator\Exception\ArgumentNullException('$fromName');
        if (!$fromAddress)
            throw new \Accelerator\Exception\ArgumentNullException('$fromAddress');
        if (!$subject)
            throw new \Accelerator\Exception\ArgumentNullException('$subject');

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
