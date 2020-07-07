<?php

namespace Monogo\Smsapi\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Dynamicfield Block
 *
 * @category SMSAPI
 * @package  Monogo|SMSAPI
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
    private $yesnoRenderer;

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
                    'Monogo\Smsapi\Block\Adminhtml\Form\Field\Status',
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
     * Get Yes No renderer
     *
     * @return \Magento\Framework\View\Element\Html\Select
     */
    protected function getYesNoRenderer()
    {
        if (!$this->yesnoRenderer) {
            try {
                $this->yesnoRenderer = $this->getLayout()->createBlock(
                    'Monogo\Smsapi\Block\Adminhtml\Form\Field\Yesno',
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
     * {@inheritDoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'col_1',
            [
                'label' => __('Status'),
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
            'col_3',
            [
                'label' => __('Message'),
                'renderer' => false,
                'class' => 'required-entry message-input',
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
