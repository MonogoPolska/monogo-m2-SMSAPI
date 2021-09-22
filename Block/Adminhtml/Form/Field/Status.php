<?php

declare(strict_types=1);

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
class Status extends Select
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
     * @param Context $context Context
     * @param CollectionFactory $collectionFactory CollectionFactory
     * @param array $data Data
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
    public function getStatusOptions(): array
    {
        if ($this->statusOptions === null) {
            $this->statusOptions = [null => __('--Select status--')];
            $statusCollection = $this->collectionFactory->create();

            foreach ($statusCollection as $status) {
                $this->statusOptions[$status->getStatus()] = __($status->getLabel());
            }
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
    public function setInputName(string $value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            foreach ($this->getStatusOptions() as $rewriteType => $rewriteLabel) {
                $this->addOption($rewriteType, addslashes((string)$rewriteLabel));
            }
        }
        return parent::_toHtml();
    }
}
