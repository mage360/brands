<?php
declare(strict_types=1);
/**
 * Mage360_Brands extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Mage360
 * @package   Mage360_Brands
 * @copyright 2018 Mage360
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Qaiser Bashir
 */
 
namespace Mage360\Brands\Model\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class Attributevalue extends AbstractSource implements ArrayInterface
{
    public $configKey = "mage360_brands/brands_general/brand_attribute";

    private $scopeConfig;

    private $eavAttribute;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Attribute $eavAttribute
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->eavAttribute = $eavAttribute;
    }

    public function getAttributeId()
    {

        return  $this->scopeConfig->getValue($this->configKey, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    /**
     * get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $attriValues = $this->getAttributeValues();

        $options = [];
        foreach ($attriValues as $key => $value) {
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $options;
    }

    public function getAttributeValues()
    {

        $attributeId = $this->getAttributeId();

        $attr = $this->getAttribute();

        $options = $attr->getOptions();

        $values = [];
        foreach ($options as $manufacturerOption) {
            $values[$manufacturerOption->getValue()] = $manufacturerOption->getLabel();  // Label
        }

        return $values;
    }

    public function getAttribute()
    {

        $attributeId = $this->getAttributeId();

        $attr = $this->eavAttribute->load($attributeId);

        return $attr;
    }
}
