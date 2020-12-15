<?php
namespace Namluu\ProductLabel\Block\Product\View;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Labels extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Namluu\ProductLabel\Helper\Config
     */
    protected $configHelper;

    protected $timezone;
    /**
     * Labels constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Namluu\ProductLabel\Helper\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Namluu\ProductLabel\Helper\Config $configHelper,
        TimezoneInterface $timezone,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configHelper = $configHelper;
        $this->timezone = $timezone;
    }

    /**
     * Get product label : new, feature, onsale, out of stock ...
     *
     * @param $attributeCode
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getProductLabel($attributeCode)
    {
        $product = $this->getProduct();
        if ($attributeCode == 'outofstock') {
            if (!$product->isAvailable()) {
                return $this->configHelper->getLabelIcon($attributeCode);
            }
        } else {
            if ($product->getData($attributeCode)) {
                return $this->configHelper->getLabelIcon($attributeCode);
            }
        }
    }

    public function getProductLabelNew()
    {
        $product = $this->getProduct();
        if ($this->isProductNew($product)) {
            return $this->configHelper->getLabelIcon('new');
        }
    }

    /**
     * Check a product is new or not
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    private function isProductNew(\Magento\Catalog\Model\Product $product): bool
    {
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();
        if (!$newsFromDate && !$newsToDate) {
            return false;
        }

        return $this->timezone->isScopeDateInInterval(
            $product->getStore(),
            $newsFromDate,
            $newsToDate
        );
    }
}
