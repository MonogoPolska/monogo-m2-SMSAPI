<?php

namespace Smsapi\Smsapi2\Model\ResourceModel\Inventory;

/**
 * Collection of Magento\CatalogInventory\Model\Stock
 *
 * @category Jti
 * @package  Jti\Reports
 * @author   Tomasz Gregorczyk (external) <tomasz.gregorczyk@monogo.pl>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
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
        $this->_init(\Magento\CatalogInventory\Model\Stock::class, \Magento\CatalogInventory\Model\ResourceModel\Stock\Item::class);
    }

    /**
     * Add store ids to filter
     *
     * @param  array $storeIds
     * @return $this
     */
    public function addStoreFilter($storeIds)
    {
        $this->addFieldToFilter('store_id', ['in' => $storeIds]);
        return $this;
    }

    /**
     * Prepare for summary report
     *
     * @param  array  $storeIds
     * @param  string $filter
     * @return $this
     */
    public function prepareForInventoryReport($storeIds, $filter = null)
    {
        $select = $this->getSelect();

        $select->columns([
            'stock_id'     => 'main_table.item_id',
            'sku'          => 'cp.sku',
            'qty'          => 'main_table.qty',
        ]);

        $select->joinLeft(['cp' => 'catalog_product_entity'], 'cp.entity_id = main_table.product_id');

        return $this;
    }
}
