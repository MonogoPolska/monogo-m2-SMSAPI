<?php

namespace Smsapi\Smsapi2\Model\ResourceModel\Sales;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as Product;

/**
 * Collection of Magento\Sales\Model\Order
 *
 * @category Jti
 * @package  Jti\Reports
 * @author   Tomasz Gregorczyk (external) <tomasz.gregorczyk@monogo.pl>
 */
class Collection extends \Smsapi\Smsapi2\Model\ResourceModel\Collection
{
    protected $product;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null,
        Product $product
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->product = $product;
    }

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
        $this->_init(\Magento\Sales\Model\Order::class, \Magento\Sales\Model\ResourceModel\Order::class);
    }

    /**
     * Prepare for sale by state report
     *
     * @param array  $storeIds
     * @param string $filter
     *
     * @return $this
     */
    public function prepareForByStateReport($storeIds, $filter = null)
    {
        $attributeResource = \Magento\Framework\App\ObjectManager::getInstance()->create('\Magento\Eav\Model\ResourceModel\Entity\Attribute');
        $wholesaleCostId = $attributeResource->getIdByCode('catalog_product', 'wholesale_cost');

        $this->getSelect()
            ->columns([
                'order_id' => 'main_table.increment_id',
                'order_status' => 'main_table.status',
                'created_at_date' => 'sfi.created_at',
                'increment_id' => 'sfi.increment_id',
                'invoice_id' => 'sfi.entity_id',
                'customer_name' => '(CONCAT(main_table.customer_firstname," ",main_table.customer_lastname))',
                'billing_address' => '(CONCAT(ba.street,", ",ba.city,", ",ba.region,", ",ba.postcode))',
                'shipping_address' => '(CONCAT(sa.street,", ",sa.city,", ",sa.region,", ",sa.postcode))',
                'product_name' => 'sfii.name',
                'product_price' => 'sfii.base_price',
                'item_qty' => 'sfii.qty',
                'base_row_total' => 'sfii.base_row_total',
                'tax_amount' => 'sfii.tax_amount',
                'excise_amount' => '(if(sfoi.parent_item_id IS NULL, sfii.excise_tax_amount, 0))',
                'discount_amount' => '(ifnull(sfii.discount_amount,0))',
                'total' => '(sfii.row_total - ifnull(sfii.discount_amount,0))',
                'wholesale_cost' => '(if(sfoi.parent_item_id IS NULL, cped.value*sfii.qty, 0))',
                'shipping_amount' => 'main_table.shipping_amount',
            ])
            ->join(['sfi' => 'sales_invoice'], 'main_table.entity_id = sfi.order_id')
            ->join(['sfii' => 'sales_invoice_item'], 'sfi.entity_id = sfii.parent_id')
            ->join(['sfoi' => 'sales_order_item'], 'sfii.order_item_id = sfoi.item_id')
            ->joinLeft(
                ['cped' => 'catalog_product_entity_decimal'],
                'sfii.product_id = cped.row_id and cped.attribute_id = ' . $wholesaleCostId
            )
            ->joinLeft(
                ['ba' => 'sales_order_address'],
                'main_table.entity_id = ba.parent_id AND ba.address_type = "billing"',
                null
            )
            ->joinLeft(
                ['sa' => 'sales_order_address'],
                'main_table.entity_id = sa.parent_id AND sa.address_type = "shipping"',
                null
            )
            ->order('sfi.created_at');

        if (isset($filter['from']) && isset($filter['to'])) {
            $this->addDateRangeFilter(
                $filter['from'],
                $filter['to'],
                'sfi'
            );
        }

        if (isset($filter['status'])) {
            $this->addOrderStatusFilter(
                $filter['status'],
                'main_table'
            );
        }

        if (isset($filter['status'])) {
            $this->addRegionFilter(
                $filter['region'],
                'sa'
            );
        }
        $this->getSelect()->group('sfii.entity_id');

        return $this;
    }

    /**
     * Prepare for state report
     *
     * @param array  $storeIds
     * @param string $filter
     *
     * @return $this
     */
    public function prepareForInStateReport($storeIds, $filter = null)
    {
        $this->getSelect()
            ->joinLeft(
                ['address' => 'sales_order_address'],
                'main_table.entity_id = address.parent_id AND address.address_type="shipping"',
                ['address.region_id', 'address.region']
            )
            ->group('address.region_id')
            ->columns([
                'qty' => 'count(*)',
                'grand_total' => 'sum(main_table.grand_total)',
                'region_id' => 'address.region_id',
                'region' => 'address.region',
            ]);

        if (isset($filter['from']) && isset($filter['to'])) {
            $this->addDateRangeFilter(
                $filter['from'],
                $filter['to']
            );
        }

        return $this;
    }

    /**
     * Prepare for specific state report
     *
     * @param array  $storeIds Store IDs
     * @param string $filter   Filter
     *
     * @return $this
     */
    public function prepareForSpecificStateReport($storeIds, $filter = null)
    {
        if (isset($filter['product_family'])) {
            $filterSku = [];

            $collection = $this->product->create();
            $collection->addAttributeToFilter(
                'avatax_excise_product_code',
                ['eq' => $filter['product_family']]
            );

            foreach ($collection as $item) {
                $filterSku[] = $item->getSku();
            }

            $this->getSelect()
                ->join(
                    ['items' => 'sales_order_item'],
                    'main_table.entity_id = items.order_id AND sku IN ("' . implode("\",\"", $filterSku) . '")',
                    ['items.sku']
                );
        }

        $this
            ->addFieldToSelect(
                new \Zend_Db_Expr('MIN(main_table.created_at) AS first_order_date')
            )
            ->addFieldToSelect(
                new \Zend_Db_Expr('MAX(main_table.created_at) AS last_order_date')
            );

        $this->getSelect()
            ->joinLeft(
                ['address' => 'sales_order_address'],
                'main_table.entity_id = address.parent_id AND address.address_type="shipping"',
                ['address.region_id', 'address.region']
            )
            ->group('address.region_id')
            ->columns(
                [
                    'qty' => 'count(*)',
                    'grand_total' => 'sum(main_table.grand_total)',
                    'region_id' => 'address.region_id',
                    'region' => 'address.region',
                ]
            );

        if (isset($filter['state'])) {
            $this->getSelect()->where('address.region = "' . $filter['state'] . '"');
        }

        if (isset($filter['from']) && isset($filter['to'])) {
            $this->addDateRangeFilter(
                $filter['from'],
                $filter['to'],
                'main_table'
            );
        }

        return $this;
    }

    /**
     * Prepare for family report
     *
     * @param array $storeIds
     * @param array $filter
     *
     * @return $this
     */
    public function prepareForFamilyReport($storeIds, $filter = [])
    {
        $attributeResource = \Magento\Framework\App\ObjectManager::getInstance()->create('\Magento\Eav\Model\ResourceModel\Entity\Attribute');
        $productGroupId = $attributeResource->getIdByCode('catalog_product', 'product_group');
        $productRangeId = $attributeResource->getIdByCode('catalog_product', 'product_range');

        if (!isset($filter['product_family'])) {
            $filter['product_family'] = '';
        }

        if (!isset($filter['period_type'])) {
            $filter['period_type'] = 'year';
        }

        $productFamily = $filter['product_family'];
        $dateGroup = strtoupper($filter['period_type']);

        $select = $this->getSelect()->reset();

        $select->from(['main_table' => 'sales_order_item'], [
            'bundle' => '(SUM(IF(cpev1.value="' . $productFamily . '" and cpev.value="bundle",qty_ordered,0)))',
            'disposable' => '(SUM(IF(cpev1.value="' . $productFamily . '" and cpev.value="disposable",qty_ordered,0)))',
            "refill" => '(SUM(IF(cpev1.value="' . $productFamily . '" and cpev.value="refill",qty_ordered,0)))',
            'device' => '(SUM(IF(cpev1.value="' . $productFamily . '" and cpev.value="device",qty_ordered,0)))',
            'accessory' => '(SUM(IF(cpev1.value="' . $productFamily . '" and cpev.value="acessory",qty_ordered,0)))',
            'date' => $this->getGroupByDate($dateGroup, 'main_table'),
        ])
            ->join(['sfo' => 'sales_order'], 'main_table.order_id = sfo.entity_id')
            ->join(
                ['cpev' => 'catalog_product_entity_varchar'],
                'main_table.product_id = cpev.row_id and cpev.attribute_id = ' . $productGroupId
            )
            ->join(
                ['cpev1' => 'catalog_product_entity_varchar'],
                'main_table.product_id = cpev1.row_id and cpev1.attribute_id = ' . $productRangeId
            )
            ->order('sfo.created_at');

        if (isset($filter['from']) && isset($filter['to'])) {
            $this->addDateRangeFilter(
                $filter['from'],
                $filter['to'],
                'sfo'
            );
        }

        $this->addGroupByDateFilter($dateGroup, 'sfo');

        return $this;
    }

    /**
     * Add store ids to filter
     *
     * @param array $storeIds
     *
     * @return $this
     */
    public function addStoreFilter($storeIds)
    {
        $this->addFieldToFilter('store_id', ['in' => $storeIds]);
        return $this;
    }
}
