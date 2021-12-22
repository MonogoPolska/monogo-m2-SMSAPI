<?php
declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class ApiButtons
 * @package Smsapi\Smsapi2\Block\Adminhtml\System\Config
 */
class ApiButtons extends ApiToken
{
    /**
     * Path to block template
     */
    protected $_template = 'Smsapi_Smsapi2::system/config/api_buttons.phtml';

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $elementOriginalData = $element->getOriginalData();
        $this->addData(
            [
                'registered_button_label' => __($elementOriginalData['registered_button_label']),
                'registered_button_url' => $elementOriginalData['registered_button_url'],
                'token_button_label' => __($elementOriginalData['token_button_label']),
                'token_button_url' => $elementOriginalData['token_button_url'],
            ]
        );
        return parent::_getElementHtml($element);
    }
}
