<?php
declare(strict_types=1);

namespace Smsapi\Smsapi2\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Escaper;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class ApitokenEnable
 * @package Smsapi\Smsapi2\Model\Config\Backend
 */
class ApitokenEnable extends Value
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;
    /**
     * @var WriterInterface
     */
    private $writerInterface;

    /**
     * Constructor
     *
     * @param WriterInterface $writerInterface
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param ManagerInterface $messageManager
     * @param Escaper $escaper
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        WriterInterface $writerInterface,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ManagerInterface $messageManager,
        Escaper $escaper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->messageManager = $messageManager;
        $this->escaper = $escaper;
        $this->writerInterface = $writerInterface;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function afterSave()
    {
        if ((string)$this->getValue() !== "oauth") {
            $this->writerInterface->delete('smsapi/oauth/refresh_token');
            $this->writerInterface->delete('smsapi/oauth/bearer');
        }
        if ((string)$this->getValue() !== "apitoken") {
            $this->writerInterface->delete('smsapi/general/apitoken');
        }
        return parent::afterSave();
    }
}
