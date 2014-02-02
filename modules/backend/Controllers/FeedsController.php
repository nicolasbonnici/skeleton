<?php

namespace modules\backend\Controllers;

/**
 * Description of FeedController
 *
 * @author info
 */

class FeedsController extends \Library\Core\AuthController {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {

    	$oFeeds = new \app\Entities\Collection\FeedCollection();
    	$oFeeds->loadByParameters(array('status' => 1));

    	$this->_view['oFeeds'] = $oFeeds;
        $this->render('feeds/index.tpl');
    }

    /**
     *     ***************************************  @todo move to CrudController
     */
    public function createAction() {
    	if (isset(
    		$this->_params['title'],
    		$this->_params['url'],
    		$this->_params['domain'],
    		$this->_params['icon']
    	)) {
    		$oFeed = new \app\Entities\Feed();

    		$oFeed->title = $this->_params['title'];
    		$oFeed->url = $this->_params['url'];
    		$oFeed->domain = $this->_params['domain'];
    		$oFeed->icon = $this->_params['icon'];

    		$this->_view['create'] = $oFeed->add();
    	}

    	$this->render('feeds/create.tpl');

    }

    public function updateAction() {

        if (isset($this->_params['id']) && !empty($this->_params['id'])) {

            // load then update item and send bool to the view
            try{
            	$oFeed = new \app\Entities\Feed($this->_params['id']);
            } catch (Exception $oException) {
                $oFeed = null;
            }

            if (
            	!is_null($oFeed) &&
            	isset(
            		$this->_params['pk'],
            		$this->_params['name'],
            		$this->_params['value'],
            		$oFeed->{$this->_params['name']}
            		)
            ) {
            	$oFeed->{$this->_params['name']} = $this->_params['value'];
	            $oFeed->lastupdate = time();

	            // Return flag to the view
	            $this->_view['update'] = $oFeed->update();
            }

            $this->_view['oFeed'] = $oFeed;

        }

        $this->render('feeds/update.tpl');
    }

}

?>
