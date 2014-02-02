<?php

namespace modules\backend\Models;

class GithubActivity {

	/**
	 * Github setup
	 */
	const TWITTER_USERNAME					= 'nicolasbonnici';
	const TWITTER_OAUTH_ACCESS_TOKEN 		= '17471981-qdnKvIpNmOMgRYEX93uG7uS7rrtOOZCY8YRNd9NWE';
	const TWITTER_OAUTH_CONSUMER_KEY 		= 'ZWgTx1j7VvOv75b3ofg';
	const TWITTER_OAUTH_ACCESS_TOKEN_SECRET = 'DF3lR7CypxHnZXaTAuoDlcoR6WVmNOcRR2LpHu2Q';
	const TWITTER_OAUTH_CONSUMER_SECRET		= 'hSmfX9oOWBBYJyQmxSvyI0aUMqoac3xze4utWunyrE';

	/**
	 * Twitter feed instance
	 * @var \app\Entities\Feed
	 */
	protected $oGithubFeed;

	/**
	 * Twitter feed items
	 * @var \app\Entities\Collection\FeedItemCollection
	 */
	protected $oGithubFeedItems;

	/**
	 * Instance constructor
	 */
	public function __construct(\app\Entities\Feed $oGithubFeed)
	{
		if (! $oGithubFeed->isLoaded()) {
			throw new TwitterActivityException('Twitter Activity feed entity is empty. You need to hydratate instance!');
		}

		$this->oGithubFeedItems = new \app\Entities\Collection\FeedItemCollection();
		$this->oGithubFeed = $oGithubFeed;
	}

	/**
	 * Parse twitter activity items then persist delta
	 * @param boolean $bSaveNewActivities	TRUE to to record FeedItem delta
	 * @param string $sRequestMethod		Github API request method POST|GET
	 * @param integer $iDelta				Feed items depth
	 * @return integer|null					Number of persisted \app\Entities\FeedItem from feed lastest activities
	 */
	public function parse($bSaveNewActivities = false, $sRequestMethod = 'GET', $iDelta = 256)
	{

		assert('\Library\Core\Validator::integer($iDelta, 0, 500) === \Library\Core\Validator::STATUS_OK');
		assert('$this->oGithubFeed->isLoaded()');
		assert('$this->oGithubFeedItems->isLoaded()');
die(var_dump(simplexml_load_string($this->oTwitterFeed->url));)
		// @see loader les derniers enregistrements de la db pour persister le diff des nouvelles activités
		if ($bSaveNewActivities) {
			$aDbElements = array();
			$oDatabaseLastFeedItems = new \app\Entities\Collection\FeedItemCollection();
			$oDatabaseLastFeedItems->loadByParameters(array('feed_idfeed' => $this->oGithubFeed->getId(), 'status' => 'publish'), array('created'=>'DESC'), array(0,50));
			// Indexer les items dans un array pour mapper plus vite
			foreach ($oDatabaseLastFeedItems as $oDbFeedItem) {
				if (isset($oDbFeedItem->created, $oDbFeedItem->title)) {
					$aDbElements[] = (int)$oDbFeedItem->created;
				}
			}
		}

		$sGetfield = '?screen_name=' . self::TWITTER_USERNAME . '&count=' . $iDelta;
		$aSettings = array(
				'oauth_access_token' => self::TWITTER_OAUTH_ACCESS_TOKEN,
				'oauth_access_token_secret' => self::TWITTER_OAUTH_ACCESS_TOKEN_SECRET,
				'consumer_key' => self::TWITTER_OAUTH_CONSUMER_KEY,
				'consumer_secret' => self::TWITTER_OAUTH_CONSUMER_SECRET
		);

		$twitter = new \Library\Twitter\TwitterAPI($aSettings);
		$json = $twitter->setGetfield($sGetfield)
		->buildOauth($this->oTwitterFeed->url, $sRequestMethod)
		->performRequest();

		foreach(json_decode($json) as $oItem) {
			$oFeedItem = new \app\Entities\FeedItem();

			$oCreated = new \DateTime($oItem->created_at);
			$oFeedItem->title = (string)$oItem->text;
			$oFeedItem->status = 'publish';
			$oFeedItem->created = (int)$oCreated->getTimestamp();
			$oPermalinks = $oItem->entities->urls;

			foreach($oPermalinks as $oPermalink) {
				// @see La structure en base ne permet pas de stocker plus d'un permalink par feedItem
				$sPermalink = (string)$oPermalink->expanded_url;
				break;
			}
			$oFeedItem->permalink = $sPermalink;
			$oFeedItem->feed_idfeed = $this->oGithubFeed->getId();

			if ($bSaveNewActivities) {
				$oAddedFeedActivities = new \Library\Core\Collection();
				if (!in_array($oFeedItem->created, $aDbElements)) {
					$oFeedItem->title = htmlentities($oFeedItem->title, ENT_QUOTES, "UTF-8");
					// @see persist delta into database
					if ($oFeedItem->add()) {
						$oAddedFeedActivities->add($oAddedFeedActivities->count()+1, $oFeedItem);
					}

				}

			}

			$this->oTwitterFeedItems->add($this->oTwitterFeedItems->count()+1, $oFeedItem);
		}

		if (isset($oAddedFeedActivities) && ($oAddedFeedActivities->count() > 0)) {
			return (int)$oAddedFeedActivities->count();
		}
		return null;
	}

	public function getFeedItems()
	{
		return $this->oTwitterFeedItems;
	}
}

class TwitterActivityException extends \Exception {}