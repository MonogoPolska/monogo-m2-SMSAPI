<?php

declare(strict_types=1);

namespace Smsapi\Smsapi2\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

/**
 * Notification Block
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Notification extends Select
{
    /**
     * Options
     *
     * @var array
     */
    private $selectOptions;

    /**
     * Get option array
     *
     * @return array
     */
    protected function getSelectOptions(): array
    {
        if ($this->selectOptions === null) {
            $this->selectOptions = [
                '0' => __('No'),
                '1' => __('Yes'),
            ];
        }
        return $this->selectOptions;
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
            foreach ($this->getSelectOptions() as $rewriteType => $rewriteLabel) {
                $this->addOption($rewriteType, addslashes((string)$rewriteLabel));
            }
        }
        return parent::_toHtml();
    }
}
