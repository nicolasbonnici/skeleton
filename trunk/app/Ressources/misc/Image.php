<?php
/**
 * Image Processing class
 * @author David <david@bazarchic.com>
 * @package core
 * @version 1.0.0 - 2013-09-11 - David <david@bazarchic.com>
 */
class core_Image
{

    public function upload()
    {
        if(move_uploaded_file($_FILES[ban_en_cours_v2][tmp_name], $_TP["config"]["server"]["path_account"]."shop/images/ban_on_v2/".$categorie_image->idcat.".".extension($_FILES[ban_en_cours_v2][type]))) {
		$new_file=$_TP["config"]["server"]["path_account"]."shop/images/ban_on_v2/".$categorie_image->idcat.".".extension($_FILES[ban_en_cours_v2][type]);
		$_TP[sftp]->TP_scp_copy($new_file);
		$info_image=getimagesize($new_file);
		$categorie_image->banniere_on_v2_ext=$_FILES[ban_en_cours_v2][type];
		$categorie_image->banniere_on_v2_l=$info_image['0'];
		$categorie_image->banniere_on_v2_h=$info_image['1'];
		$categorie_image->add();
	}
    }
    
    /**
     * Scales an image 
     * @param integer $iCategoryId
     * @param string $sFormat
     */
    public function resize($iCategoryId, $sFormat)
    {
        
        
        
        
        $sMainSourceURI = "http://cdn.bazarchic.com/images2";
        $aFormatCorrespondance = array (
            'img_background_retina_ipad'   => array ('sType' => 'retina', 'iWidth' => 2048, 'iHeight' => 1408),
            'img_background_lowd_ipad'     => array ('sType' => 'retina', 'iWidth' => 1024, 'iHeight' => 704),
            'img_background_retina_iphone' => array ('sType' => 'ipad_v', 'iWidth' => 640, 'iHeight' => 1008),
            'img_background_lowd_iphone'   => array ('sType' => 'ipad_v', 'iWidth' => 320, 'iHeight' => 480),
            'actu_mise_en_avant_retina'    => array ('sType' => 'ipad_h', 'iWidth' => 982, 'iHeight' => 846),
            'actu_mise_en_avant_lowd'      => array ('sType' => 'ipad_h', 'iWidth' => 491, 'iHeight' => 423),
            'actu_vente_retina_ipad'       => array ('sType' => 'ipad_h', 'iWidth' => 482, 'iHeight' => 412),
            'actu_vente_lowd_ipad'         => array ('sType' => 'ipad_h', 'iWidth' => 241, 'iHeight' => 206),
            'actu_vente_lowd_iphone'       => array ('sType' => 'iphone_actu_v2', 'iWidth' => 179, 'iHeight' => 78),
            'actu_vente_retina_iphone'     => array ('sType' => 'iphone_actu_v2', 'iWidth' => 358, 'iHeight' => 155)
        );

        if (isset($this->_params['iCategoryId']) && isset($this->_params['sFormat'])) {
            $iCategoryId = str_replace('.jpg', '', $this->_params['iCategoryId']);
            $sFormat = $this->_params['sFormat'];

            if (isset($aFormatCorrespondance[$sFormat]['sType'])) {
                // recreate image URL
                $sPictureURL = '/var/www/sites/bazarchicv2/shop/images/' . $aFormatCorrespondance[$sFormat]['sType'] . '/' . $iCategoryId . '.jpg';

                list($iImageWidth, $iImageHeight) = getimagesize($sPictureURL);

                // Resample the image
                $image = new Imagick($sPictureURL);
                // get source image
                // calculate image ratio
                $fWidthRatio = $aFormatCorrespondance[$sFormat]['iWidth'] / $iImageWidth;
                $fHeightRatio = $aFormatCorrespondance[$sFormat]['iHeight'] / $iImageHeight;

                // check ratio
                if ($fWidthRatio >= $fHeightRatio) {
                    // Calculate new size thanks to the width
                    $iNewWidth = $aFormatCorrespondance[$sFormat]['iWidth'];
                    $iNewHeight = $fWidthRatio * $iImageHeight;
                    $bWithWidth = true;
                }
                else {
                    // Calculate new size thanks to the height
                    $iNewHeight = $aFormatCorrespondance[$sFormat]['iHeight'];
                    $iNewWidth = $fHeightRatio * $iImageWidth;
                    $bWithWidth = false;
                }

                /****************
                 * Resize image *
                 ****************/
                $iNewWidth=round($iNewWidth,0);
                $iNewHeight=round($iNewHeight,0);

                $image->thumbnailImage($iNewWidth, $iNewHeight, imagick::FILTER_LANCZOS);
                header('Content-Type: image/jpeg');
                $fLeft = ($iNewWidth - $aFormatCorrespondance[$sFormat]['iWidth'])/2;
                $fTop = ($iNewHeight - $aFormatCorrespondance[$sFormat]['iHeight'])/2;

                $crop_width = $aFormatCorrespondance[$sFormat]['iWidth'];
                $crop_height = $aFormatCorrespondance[$sFormat]['iHeight'];
                $image->cropImage  ($crop_width, $crop_height, $fLeft, $fTop);
                echo $image;
            }
        }
    }

}
