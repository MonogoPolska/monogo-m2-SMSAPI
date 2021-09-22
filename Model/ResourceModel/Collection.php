<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Zend_Db_Expr;

class Collection extends AbstractCollection
{
    /**
     * @param null $from
     * @param null $to
     * @param null $table
     * @return $this
     */
    public function addDateRangeFilter($from = null, $to = null, $table = null): Collection
    {
        $column = $table ? "{$table}.created_at" : 'created_at';

        $from = date('Y-m-d h:i:s', strtotime($from));
        $to = date('Y-m-d h:i:s', strtotime($to));

        $this->getSelect()
            ->where("{$column} >= ?", $from)
            ->where("{$column} <= ?", $to);

        return $this;
    }

    /**
     * @param mixed $value
     * @param null $table
     * @return $this
     */
    public function addGroupByDateFilter($value, $table = null): Collection
    {
        $select = $this->getSelect();
        $column = $table ? "{$table}.created_at" : 'created_at';

        switch ($value) {
            case 'YEAR':
                $select->group(new Zend_Db_Expr("(YEAR({$column}))"));
                break;
            case 'MONTH':
                $select->group(new Zend_Db_Expr("(YEAR({$column}))"));
                $select->group(new Zend_Db_Expr("(MONTH({$column}))"));
                break;
            case 'DAY':
                $select->group(new Zend_Db_Expr("(YEAR({$column}))"));
                $select->group(new Zend_Db_Expr("(MONTH({$column}))"));
                $select->group(new Zend_Db_Expr("(DAY({$column}))"));
                break;
            default:
                $select->group(new Zend_Db_Expr("(YEAR({$column}))"));
                break;
        }

        return $this;
    }

    /**
     * @param $value
     * @param null $table
     * @return string|null
     */
    public function getGroupByDate($value, $table = null): ?string
    {
        $column = $table ? "{$table}.created_at" : 'created_at';

        switch ($value) {
            case 'YEAR':
                return "DATE_FORMAT({$column}, '%Y')";

            case 'MONTH':
                return "DATE_FORMAT({$column},'%Y-%m')";

            case "DAY":
                return "DATE_FORMAT({$column},'%Y-%m-%d')";

            default:
                return "DATE_FORMAT({$column},'%Y')";
        }
    }

    /**
     * @param int $value
     * @param null $table
     * @return $this
     */
    public function addOrderStatusFilter($value = 0, $table = null): self
    {
        if ($value) {
            $value = is_array($value) ? $value[0] : $value;
            $column = $table ? "{$table}.status" : 'status';
            $this->getSelect()->where("{$column} IN (?)", explode(',', $value));
        }

        return $this;
    }
}
