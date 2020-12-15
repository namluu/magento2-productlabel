<?php

namespace Namluu\ProductLabel\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Config extends AbstractHelper
{
    const XML_PATH_PRODUCT_LABEL_ICON_PATTENT = 'product_label/setting/';

    const LABEL_PATH = 'catalog/product/label';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuidler;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $fileDriver;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * Config constructor.
     * @param Context $context
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct($context);
        $this->urlBuidler = $urlBuilder;
        $this->fileDriver = $fileDriver;
        $this->directoryList = $directoryList;
    }

    /**
     * @param $labelCode
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getLabelIcon($labelCode)
    {
        $storedIconValue = $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_LABEL_ICON_PATTENT . $labelCode
        );
        $iconPath = self::LABEL_PATH . '/' . $storedIconValue;
        if ($storedIconValue) {
            if ($this->fileDriver->isExists($this->directoryList->getPath(DirectoryList::MEDIA) . '/' . $iconPath)) {
                return $this->urlBuidler->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) .
                    $iconPath;
            }
        }
    }
}
