<?php
namespace Smsapi\Smsapi2\Block\Sales\OrderitemsExport\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

/**
 * Class Decimal
 *
 * @package Smsapi\Smsapi2\Block\Sales\OrderitemsExport\Grid\Renderer
 */
class Decimal extends AbstractRenderer
{

    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return array|mixed|string|null
     */
    public function render(\Magento\Framework\DataObject $row) {
        $data = $row->getData($this->getColumn()->getIndex());
        if ($data) {
            $decimals = $this->getColumn()->getDecimals() ? $this->getColumn()->getDecimals() : 2;
            $negative = $this->getColumn()->getNegative() ? true : false;
            $value = floatval($data);
            if ($negative) {
                $value *= -1;
            }


            $value = number_format($value, $decimals);

            return $value;
        }
        return $data;
    }

}
