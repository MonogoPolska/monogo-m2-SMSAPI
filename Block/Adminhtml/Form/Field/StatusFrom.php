<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

/**
 * Status Block
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class StatusFrom extends Select
{
    /**
     * Options
     *
     * @var array
     */
    private $statusOptions;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Type constructor.
     *
     * @param \Magento\Framework\View\Element\Context                           $context           Context
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $collectionFactory CollectionFactory
     * @param array                                                             $data              Data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get rewrite status array
     *
     * @return array
     */
    public function getStatusOptions()
    {
        if ($this->statusOptions === null) {
            $this->statusOptions = ['any' => __('Any')];

            $statusCollection = $this->collectionFactory->create();
            foreach ($statusCollection as $status) {
                $this->statusOptions[$status->getStatus()] = __($status->getLabel());
            }

            $this->statusOptions['new'] = __('New order');
        }
        return $this->statusOptions;
    }

    /**
     * Set input name
     *
     * @param string $value Value
     *
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getStatusOptions() as $rewriteType => $rewriteLabel) {
                $this->addOption($rewriteType, addslashes($rewriteLabel));
            }
        }
        return parent::_toHtml();
    }
}
