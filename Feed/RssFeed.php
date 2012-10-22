<?php

namespace Accelerator\Feed;

/**
 * Handle a RSS 2.0 feed.
 *
 * @author gg00xiv
 */
class RssFeed {

    private $xmlObj = null;
    private $title;
    private $description;
    private $link;
    private $items;

    public function __construct($xmlObj = null) {
        if ($xmlE instanceof \SimpleXMLElement)
            $this->xmlObj = $xmlObj;
        else if (is_string($xmlObj))
            $this->xmlObj = new \SimpleXMLElement($xmlObj);
        else if ($xmlObj !== null)
            throw new \Accelerator\Exception\ArgumentException('$xmlObj', 'Only xml string or SimpleXMLElement allowed.');
    }

    /**
     * Create an instance of RssFeed
     * @param type $url 
     */
    public static function loadUrl($url) {
        $content = file_get_contents($url);
        $x = new SimpleXmlElement($content);

        return new RssFeed($x);
    }

    public function getTitle() {
        return $this->title? : $this->xmlObj->channel->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description? : $this->xmlObj->channel->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getLink() {
        return $this->link? : $this->xmlObj->channel->link;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function addItem($rssItemOrTitle, $description = null, $pubDate = null, $link = null) {
        if (!$this->items)
            $this->items = array();

        if ($rssItemOrTitle instanceof RssItem)
            $this->items[] = $rssItemOrTitle;
        else if (is_string($rssItemOrTitle) && $description && $pubDate) {
            $item = new RssItem();
            $item->setTitle($rssItemOrTitle);
            $item->setDescription($description);
            $item->setPubDate($pubDate);
            $item->setLink($link);
            $this->items[] = $item;
        } else {
            throw new \Accelerator\Exception\ArgumentException('$rssItemOrTitle', 'Invalid combination.');
        }
    }

    public function getXml() {
        $xml = '<?xml version="1.0" encoding="utf8" ?>' . "\n" .
                '<rss version="2.0">' . "\n" .
                "\t" . '<channel>' . "\n" .
                ($this->getTitle() ? "\t\t" . '<title>' . $this->getTitle() . '</title>' . "\n" : '') .
                ($this->getDescription() ? "\t\t" . '<descriptino>' . $this->getDescription() . '</description>' . "\n" : '') .
                "\t\t" . '<lastBuildDate>' . date(DATE_RFC822) . '</lastBuildDate>' . "\n" .
                ($this->getLink() ? "\t\t" . '<link>' . $this->getLink() . '</link>' . "\n" : '');
        if ($this->items) {
            foreach ($this->items as $item) {
                $xml.=$item;
            }
        }
        $xml.="\t" . '</channel>' . "\n" . '</rss>';
        return $xml;
    }

    /**
     * Save current feed content to a file on disk.
     * 
     * @param string $filename
     */
    public function save($filename) {
        file_put_contents($filename, $this->getXml());
    }

    public function __toString() {
        return $this->getXml();
    }

}

?>