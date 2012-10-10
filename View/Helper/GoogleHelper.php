<?php

namespace Accelerator\View\Helper;

/**
 * Description of GoogleHelper
 *
 * @author gg00xiv
 */
abstract class GoogleHelper {

    public static function getAdsenseCode($slot, $width, $height, $clientId) {
        ob_start();
        ?>
        <div class="pub">
            <script type="text/javascript"><!--
                google_ad_client = "ca-pub-<?= $clientId ?>";
                google_ad_slot = "<?= $slot ?>";
                google_ad_width = <?= $width ?>;
                google_ad_height = <?= $height ?>;
                //-->
            </script>
            <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function getAnalyticsCode($accountId) {
        ?>
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?= $accountId ?>']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

            })();

            $('#q').focus();
            SyntaxHighlighter.all();

        </script>
        <?php
    }

}
?>
