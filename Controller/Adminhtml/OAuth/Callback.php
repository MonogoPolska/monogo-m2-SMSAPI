<?php
declare(strict_types=1);

namespace Smsapi\Smsapi2\Controller\Adminhtml\OAuth;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\AuthorizationException;
use Smsapi\Smsapi2\Model\SmsapiCodeManagement;
use \Smsapi\Smsapi2\Helper\OauthHelper;
use Magento\Backend\Model\UrlInterface;

/**
 * Class Callback
 * @package Smsapi\Smsapi2\Controller\Adminhtml\OAuth
 */
class Callback extends Action
{

    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Smsapi_Smsapi::config';
    /**
     * @var SmsapiCodeManagement
     */
    protected $smsapiCodeManagement;
    /**
     * @var OauthHelper
     */
    protected $oauthHelper;
    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * Callback constructor.
     * @param Action\Context $context
     * @param SmsapiCodeManagement $smsapiCodeManagement
     * @param OauthHelper $oauthHelper
     * @param UrlInterface $urlInterface
     */
    public function __construct(
        Action\Context $context,
        SmsapiCodeManagement $smsapiCodeManagement,
        OauthHelper $oauthHelper,
        UrlInterface $urlInterface
    ) {
        parent::__construct($context);
        $this->smsapiCodeManagement = $smsapiCodeManagement;
        $this->oauthHelper = $oauthHelper;
        $this->urlInterface = $urlInterface;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResponseInterface
    {
        try {
            $code = (string)$this->getRequest()->getParam('code');
            $result = $this->smsapiCodeManagement->getSmsapiCode($code);
            if (!$result) {
                throw new AuthorizationException(__('Authorization error, try again.'));
            }
            $this->messageManager->addSuccessMessage(__('Authorization was successful.'));
        } catch (AuthorizationException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (Exception $exception) {
            $this->messageManager->addExceptionMessage(
                $exception,
                __('Something went wrong.')
            );
        }
        $url = $this->urlInterface->getUrl('adminhtml/system_config/edit/section/smsapi');
        return $this->_redirect($url);
    }
}
