<?php

namespace modules\frontend\Models;

class FeedItem {

	/**
	 * FeedItem's collection
	 *
	 * @var \app\Entities\Collection\FeedItemCollection
	 */
	protected $oItems;

	public function __construct()
	{
		$this->oItems = new \app\Entities\Collection\FeedItemCollection();
	}

	/**
	 * Load collection for one or more specified feeds
	 *
	 * @param integer|array Just a int or an array of integers \app\Feed primary key value
	 */
	public function loadByFeed($mFeedId, array $aLimit = array(0,64), $sStatus = 'publish')
	{
		assert('\Library\Core\Validator::string($sStatus, 1) === \Library\Core\Validator::STATUS_OK');
		assert('$this->oItems instanceOf \app\Entities\Collection\FeedItemCollection');
		assert('is_array($mFeedId) || is_int($mFeedId)');

		try {
			$this->oItems->loadByParameters(// @todo ajouter le support des array en param et faire des IN ensuite
					array(
							'status'		=> $sStatus,
							'feed_idfeed'	=> $mFeedId
					),
					array(
							'created' => 'DESC'
					),
					$aLimit
			);
			// @todo virer ce fix
			foreach ($this->oItems as $oItem) {
				$oItem->title = utf8_encode($oItem->title);
			}
			$this->oItems->rewind();
		} catch (\Library\Core\EntityException $oException) {
			// @todo ajouter une methode pour reset la collection
		}
	}

	public function getItems()
	{
		return $this->oItems;
	}

}

class FeedItemModelException extends \Exception {}