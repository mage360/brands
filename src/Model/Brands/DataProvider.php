<?php
declare(strict_types=1);

namespace Mage360\Brands\Model\Brands;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Mage360\Brands\Model\ResourceModel\Brands\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    public $loadedData;

    /**
     * @var PoolInterface
     */
    public $pool;
    
    /**
     * @var CollectionFactory
     */
    public $collection;

    /**
     * @param string            $name
     * @param string            $primaryFieldName
     * @param string            $requestFieldName
     * @param CollectionFactory $brandsCollectionFactory
     * @param PoolInterface     $pool
     * @param array             $meta
     * @param array             $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $brandsCollectionFactory,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        $this->collection   = $brandsCollectionFactory;
        $this->pool         = $pool;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    
    /**
     * Get Collection
     *
     * @return object
     */
    public function getCollection()
    {
        return $this->collection->create();
    }
    
    /**
     * Get Meta
     *
     * @return array
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        return $this->prepareMeta($meta);
    }
    
    /**
     * Prepares Meta
     *
     * @param  array $meta
     * @return array
     */
    public function prepareMeta($meta)
    {
       /**
        * @var ModifierInterface $modifier
        */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        /**
         * @var ModifierInterface $modifier
         */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $this->data = $modifier->modifyData($this->data);
        }
        return $this->data;
    }
}
