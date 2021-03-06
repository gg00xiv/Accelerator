<?php

namespace Accelerator\Feed;

/**
 * Description of RssItem
 *
 * @author gg00xiv
 */
class RssItem {

    private $xmlObj = null;
    private $title;
    private $description;
    private $language;
    private $guid;
    private $author;
    private $category;

    public function __construct($xmlObj = null) {
        if ($xmlObj instanceof \SimpleXMLElement)
            $this->xmlObj = $xmlObj;
        else if (is_string($xmlObj))
            $this->xmlObj = new \SimpleXMLElement($xmlObj);
        else if ($xmlObj !== null)
            throw new \Accelerator\Exception\ArgumentException('$xmlObj', 'Only xml string or SimpleXMLElement allowed.');
    }

    public function getTitle() {
        return $this->title? : $this->xmlObj->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description? : $this->xmlObj->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPubDate() {
        return $this->pubDate? : strtotime($this->xmlObj->pubDate);
    }

    public function setPubDate($pubDate) {
        if (is_string($pubDate))
            $this->pubDate = strtotime($pubDate);
        else if (is_int($pubDate))
            $this->pubDate = $pubDate;
        else
            throw new \Accelerator\Exception\ArgumentException('$pubDate', 'Only time() type or date as string allowed.');
    }

    public function getLanguage() {
        return $this->language? : $this->xmlObj->language;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function getGuid() {
        return $this->guid? : $this->xmlObj->guid;
    }

    public function setGuid($guid) {
        $this->guid == $guid;
    }

    public function getAuthor() {
        return $this->author? : $this->xmlObj->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getCategory() {
        return $this->category? : $this->xmlObj->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getLink() {
        return $this->link? : $this->xmlObj->link;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function getXml() {
        return "\t\t" . '<item>' . "\n" .
                ($this->getTitle() ? "\t\t\t" . '<title>' . $this->getTitle() . '</title>' . "\n" : '') .
                ($this->getDescription() ? "\t\t\t" . '<description><![CDATA[' . $this->getDescription() . ']]></description>' . "\n" : '') .
                ($this->getPubDate() ? "\t\t\t" . '<pubDate>' . date(DATE_RFC822, $this->getPubDate()) . '</pubDate>' . "\n" : '') .
                ($this->getLanguage() ? "\t\t\t" . '<language>' . $this->getLanguage() . '</language>' . "\n" : '') .
                ($this->getAuthor() ? "\t\t\t" . '<author>' . $this->getAuthor() . '</author>' . "\n" : '') .
                ($this->getCategory() ? "\t\t\t" . '<category>' . $this->getCategory() . '</category>' . "\n" : '') .
                ($this->getLink() ? "\t\t\t" . '<link>' . $this->getLink() . '</link>' . "\n" : '') .
                "\t\t" . '</item>' . "\n";
    }

    public function __toString() {
        return $this->getXml();
    }

}

?>