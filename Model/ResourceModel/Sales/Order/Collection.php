<?php

namespace Smsapi\Smsapi2\Model\ResourceModel\Sales\Order;

/**
 * Collection of Magento\Sales\Model\Order
 *
 * @category Jti
 * @package  Jti\Reports
 * @author   Tomasz Gregorczyk (external) <tomasz.gregorczyk@monogo.pl>
 */
class Collection extends \Smsapi\Smsapi2\Model\ResourceModel\Collection
{
    /**
     * Aggregated Data Table
     *
     * @var string
     */
    protected $_aggregationTable = 'sales_order';

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
     * Collect columns for collection
     *
     * @return array
     */
    protected function _getSelectedColumns()
    {
        $connection = $this->getConnection();
        $this->_periodFormat = $connection->getDateFormatSql('created_at', '%Y-%m-%d');

        $this->_selectedColumns = [
                'entity_id',
                'status',
                'customer_id',
                'created_at',
                'increment_id',
                'coupon_code',
                'coupon_rule_name',
                'discount_amount',
            ];

        return $this->_selectedColumns;
    }

    /**
     * Set interval
     * @codeCoverageIgnore
     *
     * @param  \DateTimeInterface $fromDate
     * @param  \DateTimeInterface $toDate
     * @return $this
     */
    public function setInterval(\DateTimeInterface $fromDate, \DateTimeInterface $toDate)
    {
        $this->_from = new \DateTime($fromDate->format('Y-m-d'), $fromDate->getTimezone());
        $this->_to = new \DateTime($toDate->format('Y-m-d'), $toDate->getTimezone());

        return $this;
    }

    /**
       * Prepare for orders with items cost report
       *
       * @param $storeIds
       * @param  array $filter
       * @return $this
       */
    public function prepareForWithItemsExportReport($storeIds, $filter = [])
    {
        $select = $this->getSelect();

        $select->columns()->reset();
        $connection = $select->getConnection();
        $select->from(['main_table' => $connection->getTableName('sales_order')]);

        $select->columns([
            'website' => 'cw.name',
            'increment_id' => 'main_table.increment_id',
            'order_chanel' => '(CASE '
                . 'WHEN main_table.remote_ip IS NULL '
                . 'THEN "TEL" '
                . 'ELSE "WEB"'
                . ' END)',
            'checkout_status' => '(CASE '
                . 'WHEN main_table.remote_ip IS NULL AND main_table.customer_id IS NOT NULL THEN "Account Tel" '
                . 'WHEN main_table.remote_ip IS NULL AND main_table.customer_id IS NULL THEN "Guest Tel" '
                . 'WHEN main_table.remote_ip IS NOT NULL AND main_table.customer_id IS NULL THEN "Guest Checkout" '
                . 'WHEN main_table.remote_ip IS NOT NULL and main_table.customer_id IS NOT NULL THEN "Account Checkout" '
                . 'ELSE "Unknown (please contact administrator)" '
                . 'END)',
            'status' => 'main_table.status',
            'oms_created_at'=>'(CASE '
                . 'WHEN shipping_address.sms_alert IS NULL '
                . 'THEN "No" '
                . 'ELSE "Yes"'
                . ' END)',
            'created_at' => 'DATE_FORMAT(main_table.created_at, "%Y-%m-%d")',
            'order_time' => 'DATE_FORMAT(main_table.created_at, "%H:%i:%s")',
            'order_currency_code' => 'main_table.order_currency_code',
            'shipping.firstname'=>'shipping_address.firstname',
            'shipping.lastname'=>'shipping_address.lastname',
            'shipping.city'=>'shipping_address.city',
            'total_items_qty' => 'main_table.total_qty_ordered',
            'subtotal_incl_tax' => 'main_table.subtotal_incl_tax',
            'product_options_order' => '', //check what should be here
            'method' => 'sfop.method',
            'shipping_country' => 'shipping_address.country_id',
        ])
            ->joinLeft(['sfop' => $connection->getTableName('sales_order_payment')], 'main_table.entity_id=sfop.parent_id', null)
            ->joinLeft(['shipping_address' => $connection->getTableName('sales_order_address')], 'main_table.entity_id=shipping_address.parent_id AND shipping_address.address_type="shipping"', null)
            ->join(['cs' => $connection->getTableName('store')], 'main_table.store_id=cs.store_id', null)
            ->join(['cw' => $connection->getTableName('store_website')], 'cs.website_id=cw.website_id', null)
            ->order('main_table.created_at');
        if (isset($filter['website_id']) && $filter['website_id']) {
            $select->where('cs.website_id =? ', $filter['website_id']);
        }

        if (isset($filter['show_order_statuses']) && $filter['show_order_statuses']) {
            if (isset($filter['order_statuses']) && $filter['order_statuses']) {
                $select->where('main_table.status IN (?) ', explode(',', $filter['order_statuses'][0]));
            }
        }
        if (isset($filter['from']) && $filter['from'] != null) {
            $from = date("Y-m-d", strtotime($filter['from']));
            $select->where('main_table.created_at > ?', $from . ' 00:00:00');
        }
        if (isset($filter['to']) && $filter['to'] != null) {
            $to = date("Y-m-d", strtotime($filter['to']));
            $select->where('main_table.created_at < ?', $to . ' 23:59:59');
        }
        return $this;
    }
}
