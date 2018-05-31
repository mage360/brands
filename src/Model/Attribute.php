<?php
declare(strict_types=1);


namespace Mage360\Brands\Model;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

class Attribute
{
    public $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        $attributes = $this->getAttributes();
        $data = [];
        $data[]  = ['value' => 0,'label' => "Please select"];
        foreach ($attributes as $key => $label) {
            $data[]  = ['value' => $key,'label' => $label];
        }

        return $data;
    }

    public function getAttributes()
    {

        $collection = $this->collectionFactory->create();

        $attr_groups = [];
        foreach ($collection as $items) {
            $is_visible = $items->getData("is_visible");
            if ($is_visible  == 1) {
                $attr_groups[$items->getData("attribute_id")] = $items->getData("frontend_label");
            }
        }

        return $attr_groups;
    }
}
