<?php

namespace modules\lifestream\Controllers;

/**
 * Lifestream HomeController
 * A social network activities and flux parser
 *
 * @author Nicolas Bonnici
 */

class HomeController extends \Library\Core\Auth {

    public function __preDispatch() {}

    public function __postDispatch() {}

    public function indexAction()
    {
        $oLifestream = new \modules\backend\Models\Lifestream(intval($this->_params['idfeed']), $this->oUser);
        $oLifestream->loadEntities(
                array(
                        'status' => 1
                )
        );
        $this->_view['oFeeds'] = $oLifestream->getEntities();
        $this->render('lifestream/index.tpl');
    }

    public function parseAction ()
    {
        if (isset($this->_params['idfeed']) && intval($this->_params['idfeed']) > 0 ) {
            try {
                $oLifestreamModel = new \modules\backend\Models\Lifestream(intval($this->_params['idfeed']), $this->oUser);
                $oFeed = $oLifestreamModel->getEntity();
                switch ($oFeed->domain) {
                	case 'twitter.com':
                	        $oTwitterActivity = new \modules\backend\Models\TwitterActivity($oFeed);
                	        die(var_dump($oTwitterActivity->parse(true)));
                	        break;
                	default:
                	    break;
                }
            } catch(\Library\Core\CrudException $oException) {
                // error
            }
        }
    }

    /**
     * Create a feed entity
     */
    public function createFeedAction()
    {
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

        $this->render('lifestream/create.tpl');

    }

    /**
     * Update a feed entity
     */
    public function updateFeedAction()
    {

        if (isset($this->_params['idfeed']) && !empty($this->_params['idfeed'])) {

            // load then update item and send bool to the view
            try{
                $oFeed = new \app\Entities\Feed($this->_params['idfeed']);
            } catch (Exception $oException) {
                $oFeed = null;
            }

            $this->_view['oFeed'] = $oFeed;

        }

        $this->render('lifestream/updateFeed.tpl');
    }

}

?>
