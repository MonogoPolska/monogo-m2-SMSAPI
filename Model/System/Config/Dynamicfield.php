<?php

namespace Smsapi\Smsapi2\Model\System\Config;

use Magento\Config\Model\Config\Backend\Serialized\ArraySerialized;

/**
 * Class Dynamicfield
 *
 * @category SMSAPI
 * @package  Smsapi|SMSAPI
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class Dynamicfield extends ArraySerialized
{
    /**
     * {@inheritDoc}
     */
    public function beforeSave()
    {
        $exceptions = $this->getValue();

        $this->setValue($exceptions);

        return parent::beforeSave();
    }
}
