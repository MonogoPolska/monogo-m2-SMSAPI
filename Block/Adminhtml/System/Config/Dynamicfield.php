<?php

namespace Smsapi\Smsapi2\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Dynamicfield Block
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Dynamicfield extends AbstractFieldArray
{
    /**
     * @var \Magento\Framework\View\Element\Html\Select
     */
    private $statusRenderer;

    /**
     * @var \Magento\Framework\View\Element\Html\Select
     */
    private $statusFromRenderer;

    /**
     * @var \Magento\Framework\View\Element\Html\Select
     */
    private $yesnoRenderer;

    /**
     * @var \Magento\Framework\View\Element\Html\Select
     */
    private $notificationRenderer;

    /**
     * Get cache type type renderer
     *
     * @return \Magento\Framework\View\Element\Html\Select
     */
    protected function getStatusRenderer()
    {
        if (!$this->statusRenderer) {
            try {
                $this->statusRenderer = $this->getLayout()->createBlock(
                    'Smsapi\Smsapi2\Block\Adminhtml\Form\Field\Status',
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
                $this->statusRenderer->setClass('customer_group_select required-entry validate-select validate-no-empty');
            } catch (\Exception $e) {
            }
        }
        return $this->statusRenderer;
    }

    /**
     * Get cache type type renderer
     *
     * @return \Magento\Framework\View\Element\Html\Select
     */
    protected function getStatusFromRenderer()
    {
        if (!$this->statusFromRenderer) {
            try {
                $this->statusFromRenderer = $this->getLayout()->createBlock(
                    'Smsapi\Smsapi2\Block\Adminhtml\Form\Field\StatusFrom',
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
                $this->statusFromRenderer->setClass('customer_group_select required-entry validate-no-empty');
            } catch (\Exception $e) {
            }
        }
        return $this->statusFromRenderer;
    }

    /**
     * Get Yes No renderer
     *
     * @return \Magento\Framework\View\Element\Html\Select
     */
    protected function getYesNoRenderer()
    {
        if (!$this->yesnoRenderer) {
            try {
                $this->yesnoRenderer = $this->getLayout()->createBlock(
                    'Smsapi\Smsapi2\Block\Adminhtml\Form\Field\Yesno',
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
                $this->yesnoRenderer->setClass('customer_group_select required-entry validate-select');
            } catch (\Exception $e) {
            }
        }
        return $this->yesnoRenderer;
    }

    /**
     * Get Yes No renderer
     *
     * @return \Magento\Framework\View\Element\Html\Select
     */
    protected function getNotificationRenderer()
    {
        if (!$this->notificationRenderer) {
            try {
                $this->notificationRenderer = $this->getLayout()->createBlock(
                    'Smsapi\Smsapi2\Block\Adminhtml\Form\Field\Notification',
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
                $this->notificationRenderer->setClass('customer_group_select required-entry validate-select');
            } catch (\Exception $e) {
            }
        }
        return $this->notificationRenderer;
    }

    /**
     * {@inheritDoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'col_6',
            [
                'label' => __('Status from'),
                'renderer' => $this->getStatusFromRenderer(),
            ]
        );
        $this->addColumn(
            'col_1',
            [
                'label' => __('Status to'),
                'renderer' => $this->getStatusRenderer(),
            ]
        );
        $this->addColumn(
            'col_2',
            [
                'label' => __('Enabled'),
                'renderer' => $this->getYesNoRenderer(),
            ]
        );
        $this->addColumn(
            'col_7',
            [
                'label' => __('Send message to client'),
                'renderer' => $this->getYesNoRenderer(),
            ]
        );
        $this->addColumn(
            'col_3',
            [
                'label' => __('Message'),
                'renderer' => false,
                'class' => 'message-input',
            ]
        );
        $this->addColumn(
            'col_4',
            [
                'label' => __('Send notification'),
                'renderer' => $this->getNotificationRenderer(),
            ]
        );
        $this->addColumn(
            'col_5',
            [
                'label' => __('Notification message'),
                'renderer' => false,
                'class' => 'message-input',
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * {@inheritDoc}
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->getStatusRenderer()->calcOptionHash($row->getData('col_1'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->getYesNoRenderer()->calcOptionHash($row->getData('col_2'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->getNotificationRenderer()->calcOptionHash($row->getData('col_4'))] =
            'selected="selected"';

        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }

    /**
     * {@inheritDoc}
     */
    public function renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new \Exception('Wrong column name specified.');
        }
        $column = $this->_columns[$columnName];
        $inputName = $this->_getCellInputElementName($columnName);

        if ($column['renderer']) {
            return $column['renderer']->setInputName(
                $inputName
            )->setId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setColumnName(
                $columnName
            )->setColumn(
                $column
            )->toHtml();
        }

        return '<textarea id="' . $this->_getCellInputElementId(
            '<%- _id %>',
            $columnName
            ) .
            '"' .
            ' name="' .
            $inputName .
            '" value="<%- ' .
            $columnName .
            ' %>" ' .
            ($column['size'] ? 'size="' .
                $column['size'] .
                '"' : '') .
            ' class="' .
            (isset($column['class'])
                ? $column['class']
                : 'input-text') . '"' . (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '></textarea><span style="display:none" class="char-counter">'
            . __('Number of characters:') . '<span class="' . $this->_getCellInputElementId(
                '<%- _id %>',
                $columnName
            ) .
            '"></span></span>';
    }
}
