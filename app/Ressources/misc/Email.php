<?php

namespace core;

/**
 * Description of core_Email
 * @author: Yaniv
 * @package    Core
 * @license: Bazarchic
 * @since: 01/08/2013, 16:33
 */
class Email
{
    /**
     * @var \db\Email
     */
    protected $oDbTmpEmail;

    /**
     * Constructeur de la classe \kernel\Email
     * @param type $iEmailId
     * @param type $sEmail
     */
    public function __construct($iEmailId, $sEmail, $iCountryId, $iLanguageId)
    {
        assert('is_null($iEmailId) || \\core\\Validator::integer($iEmailId, 1) === \\core\\Validator::STATUS_OK');

        $this->oDbTmpEmail = new \db\EmailLanguesPays();

        try {
            $this->oDbTmpEmail->loadByCountryLanguage($iEmailId, $iCountryId, $iLanguageId);
        } catch (\core\ObjectException $oException) {
            // Load fr_FR email if no mail exists for given country/language
            $this->oDbTmpEmail->loadByCountryLanguage($iEmailId, 250, 1);
        }

        require_once ROOT . '/library/external/swiftmailer/lib/swift_required.php';
    }

    /**
     * Encrypt/decrypt given text for tracking
     * @param string $sText
     * @param string $bEncrypt
     * @return string Encrypted/decrypted message
     */
    static public function crypto($sText, $bEncrypt = false)
    {
        if ($bEncrypt) {
            return base64_decode(str_replace(array('-', '_', '$'), array('+', '/', '='), $sText));
        } else {
            return str_replace(array('+', '/', '='), array('-', '_', '$'), base64_encode($sText));
        }
    }

    /**
     * Methode qui envoi le/les mails
     * @param array $aParameters
     * @param integer $iPriority
     * @return boolean renvoi un bouléen
     */
    public function sendEmail($aParameters, $iPriority = 3)
    {
        assert('isset($aParameters["email"]) && \\core\\Validator::email($aParameters["email"]) === \\core\\Validator::STATUS_OK');

        $this->replaceKeywords($aParameters);
        $sContent = $this->addTrackingMails($this->oDbTmpEmail->idemail, $aParameters['email']);

        $oMessage = \Swift_Message::newInstance()

                // Give the message a subject
                ->setSubject($this->oDbTmpEmail->sujet)

                // Set the From address with an associative array
                ->setFrom(array('notification@bazarchic.com' => 'BazarChic'))

                // Set the To addresses with an associative array
                ->setTo(array($aParameters['email']))

                // Give it a body
                ->setBody($sContent, 'text/html', 'utf-8')

                ->addPart($this->oDbTmpEmail->corps_txt, 'text/plain')

                // Indicate "High" priority
                ->setPriority($iPriority);

        $oMailer = \Swift_Mailer::newInstance(\Swift_SendmailTransport::newInstance());
        $iResult = $oMailer->send($oMessage);

        if ($iResult === 0) {
            throw new \Exception('No message sent');
        }

        if (rand(0, 10) === 5) {
            $oMessage->setTo('crm@bazarchic-team.com');
            $oMailer->send($oMessage);
        }

        return $iResult;
    }

    /**
     * methode qui construit le body du mail temporaire
     * @param array $aKeywords List of keywords/values to replace
     */
    protected function replaceKeywords(array $aKeywords)
    {
        foreach ($aKeywords as $sKeyword => $sValue) {
            $this->oDbTmpEmail->sujet       = str_replace("[$sKeyword]", $sValue, $this->oDbTmpEmail->sujet);
            $this->oDbTmpEmail->corps_txt   = str_replace("[$sKeyword]", $sValue, $this->oDbTmpEmail->corps_txt);
            $this->oDbTmpEmail->corps_html  = str_replace("[$sKeyword]", $sValue, $this->oDbTmpEmail->corps_html);
        }

        $this->oDbTmpEmail->sujet       = html_entity_decode($this->oDbTmpEmail->sujet, ENT_QUOTES, 'UTF-8');
        $this->oDbTmpEmail->corps_html  = html_entity_decode($this->oDbTmpEmail->corps_html, ENT_QUOTES, 'UTF-8');
    }

    /**
     * methode qui rajoute les infos de tracking au mail
     * @param integer $iEmailId Sent email ID
     * @param string $sEmail Email recipient address
     * @return string HTML content of mail
     */
    protected function addTrackingMails($iEmailId, $sEmail)
    {
        global $iTrackingMailId;

        $oMember = new \db\Membres();
        $oTrackingMails = new \db\TrackingMails();
        $oTrackingMails->date_envoi = date('Y-m-d');
        $oTrackingMails->idemail = $iEmailId;
        $oTrackingMails->idmembre = $oMember->loadByEmail($sEmail) ? $oMember->idmembre : 0;
        $oTrackingMails->add();
        $iTrackingMailId = $oTrackingMails->getId();

        $sContent = preg_replace_callback(
            '/<a href="([^"]*)"/',
            function(array $aMatches) {
                global $iTrackingMailId;

                return str_replace(
                    $aMatches[1],
                    'http://fr.bazarchic.com/redirection_tracking.php?track=' . \core\Email::crypto($iTrackingMailId . '##' . $aMatches[1]),
                    $aMatches[0]
                );
            },
            $this->oDbTmpEmail->corps_html
        );
        return '<html><body>' . $sContent . '<img src="http://fr.bazarchic.com/pixel_tracking.php?track=' . self::crypto($iTrackingMailId) . '" width="1" height="1"></body></html>';
    }
}
