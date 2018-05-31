<?php
declare(strict_types=1);

namespace Mage360\Brands\Ui\DataProvider\Brands\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Mage360\Brands\Model\ResourceModel\Brands\CollectionFactory;

class BrandsData implements ModifierInterface
{
    /**
     * @var \Mage360\Brands\Model\ResourceModel\Brands\Collection
     */
    public $collection;

    /**
     * @param CollectionFactory $brandsCollectionFactory
     */
    public function __construct(
        CollectionFactory $brandsCollectionFactory
    ) {
        $this->collection = $brandsCollectionFactory;
    }
    
    /*
     * Get Collection
     */
    public function getCollection()
    {
        return $this->collection->create();
    }
    
    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function modifyData(array $data)
    {
        $collection = $this->getCollection();
        
        $items = $collection->getItems();
        /**
         * @var $brand \Mage360\Brands\Model\Brands
         */
        foreach ($items as $brand) {
            $_data = $brand->getData();
            if (isset($_data['logo_path'])) {
                $image = [];
                $image[0]['name'] = $brand->getLogoPath();
                $image[0]['url'] = $brand->getLogoPathUrl();
                $_data['logo_path'] = $image;
            }
            $brand->setData($_data);
            $data[$brand->getId()] = $_data;
        }

        return $data;
    }
}
