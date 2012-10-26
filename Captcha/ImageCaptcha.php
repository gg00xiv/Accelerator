<?php

namespace Accelerator\Captcha;

/**
 * Not yet validated. do not use.
 *
 * @author gg00xiv
 */
class ImageCaptcha extends Captcha {

    protected $nbDigits;
    protected $size;
    private $generatedImage = null;
    private $generatedValue = null;

    public function __construct($nbDigits = 6, $size = array(80, 25)) {
        $this->nbDigits = $nbDigits;
        $this->size = $size;
    }

    protected function generate(&$captchaValue) {
        if ($this->generatedValue === null) {
            $img = imagecreate($this->size[0], $this->size[1]);

            $backgroundColor = imagecolorallocate($img, 0, 0, 0);
            imagefill($img, 0, 0, $backgroundColor);

            $foregroundColor = imagecolorallocate($img, 255, 255, 255);

            $i = 0;
            $this->generatedValue = '';
            while ($i < $this->nbDigits) {
                $digit = mt_rand(0, 9); // On génère le nombre aléatoire
                $this->generatedValue.=$digit;
                $i++;
            }
            
            imagestring($img, 5, 10, 5, $this->generatedValue, $foregroundColor);

            ob_start();
            imagepng($img);
            $this->generatedImage = ob_get_clean();
            
            imagedestroy($img);
        }

        $captchaValue = $this->generatedValue;
        return $this->generatedImage;
    }

    /**
     * Save current image captcha to disk.
     * 
     * @param string $filename
     */
    public function save($filename) {
        file_put_contents($filename, $this->generate($captchaValue));
    }

}

?>
