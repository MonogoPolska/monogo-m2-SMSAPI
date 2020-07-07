<?php

namespace Monogo\Smsapi\Block\Adminhtml\System\Config\Template;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Info Block
 *
 * @category SMSAPI
 * @package  Monogo|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Info extends Field
{
    /**
     * Template path
     *
     * @var string
     */
    protected $_template = 'system/config/messages_template/info.phtml';

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element Element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $columns = $this->getRequest()
            ->getParam('website') || $this->getRequest()->getParam('store') ? 5 : 4;
        return $this->_decorateRowHtml(
            $element,
            "<td colspan='{$columns}'>" . $this->toHtml() . '</td>'
        );
    }
}
