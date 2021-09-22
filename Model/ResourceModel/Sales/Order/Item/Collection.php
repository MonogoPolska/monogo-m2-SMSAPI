<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Model\ResourceModel\Sales\Order\Item;

use Magento\Sales\Model\ResourceModel\Order\Item;

/**
 * Collection of Magento\Sales\Model\Order\Item
 *
 * @category Jti
 * @package  Jti\Reports
 * @author   Tomasz Gregorczyk (external) <tomasz.gregorczyk@monogo.pl>
 */
class Collection extends \Smsapi\Smsapi2\Model\ResourceModel\Collection
{
    /**
     * Fields map for correlation names & real selected fields
     *
     * @var array
     */
    protected $_map = ['fields' => ['store_id' => 'main_table.store_id']];

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magento\Sales\Model\Order\Item::class, Item::class);
    }

    /**
     * Add store ids to filter
     *
     * @param array $storeIds
     * @return $this
     */
    public function addStoreFilter(array $storeIds): self
    {
        $this->addFieldToFilter('store_id', ['in' => $storeIds]);
        return $this;
    }

    /**
     * Prepare for summary report
     *
     * @param array $storeIds
     * @param null $filter
     * @return $this
     */
    public function prepareForSummaryReport(array $storeIds, $filter = null): self
    {
        if (is_array($storeIds) && !empty($storeIds)) {
            $this->addFieldToFilter('store_id', ['in' => $storeIds]);
        }

        return $this;
    }
}
