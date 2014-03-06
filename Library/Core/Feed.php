<?php

namespace Library\Core;

/**
 * Feed generator and parser class
 *
 * @author Nicolas Bonnici <nicolasbonnici@gmail.com>
 *
 */
interface Feed {

    /**
     * Feed instance
     * @var \app\Entities\Feed
     */
    protected $oFeed;

    /**
     * Feed items
     * @var \app\Entities\Collection\FeedItemCollection
     */
    protected $oFeedItems;

    /**
     * Instance constructor
     */
    public function __construct()
    {
        $this->oFeed = $oFeed = new \app\Entities\Feed();
        $this->oFeedItems = new \app\Entities\Collection\FeedItemCollection();
    }

    /**
     * Parse items from feed
     * @param boolean $bPersistNewFeedItem    TRUE to store feed items delta
     * @param integer $iDelta                Feed items depth
     * @return integer|null                    Number of persisted \app\Entities\FeedItem from feed lastest activities
     */
    public function parse($bPersistNewFeedItem = false, $iDelta = 256) {}

    public function generate() {}

    public function getFeedItems()
    {
        return $this->oFeedItems;
    }
}

class FeedException extends \Exception {}

?>
