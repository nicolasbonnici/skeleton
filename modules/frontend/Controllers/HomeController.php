<?php

namespace modules\frontend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
class HomeController extends \Library\Core\Controller {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {

        $this->render('home/index.tpl');
    }

    public function lifestreamAction()
    {
    	$oFeeds = new \app\Entities\Collection\FeedCollection();
    	$oFeeds->load();
    	$this->_view['oFeeds'] = $oFeeds;
        $this->render('home/lifestream.tpl');
    }

    public function portfolioAction()
    {
        $this->render('home/portfolio.tpl');
    }

    public function contactAction()
    {
        $this->render('home/contact.tpl');
    }

    public function listAction(array $aFeedIds = array(1,2,3), $iLoadStep = 64) {

		$aLimit = array(0, $iLoadStep);

    	if (isset($this->_params['istep']) && $this->_params['istep'] > 0) {
    		$aLimit = array($this->_params['istep'], $iLoadStep);
    	}

    	if (isset($this->_params['sfeedid'])) {
    		$aFeedIds = explode(',', $this->_params['sfeedid']);
    	}

    	$oFeedItems = new \modules\frontend\Models\FeedItem();
		$oFeedItems->loadByFeed($aFeedIds, $aLimit);

    	$this->_view['oItems'] = $oFeedItems->getItems();

    	$this->render('home/list.tpl');
    }

}

?>
