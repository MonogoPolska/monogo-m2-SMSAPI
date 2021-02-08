<?php

namespace Smsapi\Smsapi2\Block\Sales\OrderitemsExport\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use \Magento\Backend\Block\Context;
use Magento\Framework\DataObject;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class Options
 *
 * @package Smsapi\Smsapi2\Block\Sales\OrderitemsExport\Grid\Renderer
 */
class Options extends AbstractRenderer
{

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Context $context,
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }

    /**
     *  Renders grid column
     *
     * @param DataObject $row
     * @return mixed|string
     */
    public function render(DataObject $row) {
        try {
            if ($row->getData('row_total') == 0) {

                $data = $row->getData('item_id');
                $item = $this->orderRepository->get($data);
                if (isset($item->getProductOptions()['options']) && sizeof($item->getProductOptions()['options'])>0) {
                    $value = [];
                    foreach ($item->getProductOptions()['options'] as $option) {
                        $value[] = trim($option['print_value']);
                    }
                    if (sizeof($value) > 1) {
                        return implode('<br/>', $value);
                    } else {
                        return $value[0];
                    }
                }
            }

        } catch (\Exception $e) {
            return "";
        }

    }

}
