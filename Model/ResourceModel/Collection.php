<?php

namespace Smsapi\Smsapi2\Model\ResourceModel;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @param  string $from
     * @param  string $to
     * @param  string $table
     * @return $this
     */
    public function addDateRangeFilter($from = null, $to = null, $table = null)
    {
        $column = $table ? "{$table}.created_at" : 'created_at';

        $from =  date('Y-m-d h:i:s', strtotime($from));
        $to =  date('Y-m-d h:i:s', strtotime($to));

        $this->getSelect()
            ->where("{$column} >= ?", $from)
            ->where("{$column} <= ?", $to);

        return $this;
    }

    public function addGroupByDateFilter($value, $table = null)
    {
        $select = $this->getSelect();
        $column = $table ? "{$table}.created_at" : 'created_at';

        switch ($value) {
            case 'YEAR':
                $select->group(new \Zend_Db_Expr("(YEAR({$column}))"));
                break;
            case 'MONTH':
                $select->group(new \Zend_Db_Expr("(YEAR({$column}))"));
                $select->group(new \Zend_Db_Expr("(MONTH({$column}))"));
                break;
            case 'DAY':
                $select->group(new \Zend_Db_Expr("(YEAR({$column}))"));
                $select->group(new \Zend_Db_Expr("(MONTH({$column}))"));
                $select->group(new \Zend_Db_Expr("(DAY({$column}))"));
                break;
            default:
                $select->group(new \Zend_Db_Expr("(YEAR({$column}))"));
                break;
        }

        return $this;
    }

    /**
     * @param  string $value
     * @param  string $table
     * @return string
     */
    public function getGroupByDate($value, $table = null)
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
     * @param  string $value
     * @param  string $table
     * @return $this
     */
    public function addOrderStatusFilter($value = 0, $table = null)
    {
        if ($value) {
            $value = is_array($value) ? $value[0] : $value;
            $column = $table ? "{$table}.status" : 'status';
            $this->getSelect()->where("{$column} IN (?)", explode(',', $value));
        }

        return $this;
    }
}
