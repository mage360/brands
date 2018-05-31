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
 */
namespace Mage360\Brands\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

use Mage360\Brands\Api\BrandsRepositoryInterface;

use Mage360\Brands\Api\Data;
use Mage360\Brands\Api\Data\BrandsInterface;
use Mage360\Brands\Api\Data\BrandsInterfaceFactory;
use Mage360\Brands\Api\Data\BrandsSearchResultsInterfaceFactory;
use Mage360\Brands\Model\ResourceModel\Brands as ResourceBrands;
use Mage360\Brands\Model\ResourceModel\Brands\Collection;
use Mage360\Brands\Model\ResourceModel\Brands\CollectionFactory as BrandsCollectionFactory;

/**
 * Class BrandsRepository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BrandsRepository implements BrandsRepositoryInterface
{
    /**
     * @var array
     */
    public $instances = [];
    /**
     * @var ResourceBrands
     */
    public $resource;
    /**
     * @var StoreManagerInterface
     */
    public $storeManager;
    /**
     * @var brandsCollectionFactory
     */
    public $brandsCollectionFactory;
    /**
     * @var BrandsSearchResultsInterfaceFactory
     */
    public $searchResultsFactory;
    /**
     * @var BrandsInterfaceFactory
     */
    public $brandsInterfaceFactory;
    /**
     * @var DataObjectHelper
     */
    public $dataObjectHelper;

    public function __construct(
        ResourceBrands $resource,
        StoreManagerInterface $storeManager,
        BrandsCollectionFactory $brandsCollectionFactory,
        BrandsSearchResultsInterfaceFactory $brandsSearchResultsInterfaceFactory,
        BrandsInterfaceFactory $brandsInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource                 = $resource;
        $this->storeManager             = $storeManager;
        $this->brandsCollectionFactory  = $brandsCollectionFactory;
        $this->searchResultsFactory     = $brandsSearchResultsInterfaceFactory;
        $this->brandsInterfaceFactory   = $brandsInterfaceFactory;
        $this->dataObjectHelper         = $dataObjectHelper;
    }
    /**
     * Save page.
     *
     * @param  \Mage360\Brands\Api\Data\BrandsInterface $brand
     * @return \Mage360\Brands\Api\Data\BrandsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(BrandsInterface $brand)
    {
        /**
         * @var BrandsInterface|\Magento\Framework\Model\AbstractModel $brand
         */
        try {
            $this->resource->save($brand);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the brand: %1',
                    $exception->getMessage()
                )
            );
        }
        return $brand;
    }

    /**
     * Retrieve Brand.
     *
     * @param  int $brandId
     * @return \Mage360\Brands\Api\Data\BrandsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($brandId)
    {
        if (!isset($this->instances[$brandId])) {
            /**
             * @var \Mage360\Brands\Api\Data\BrandsInterface|\Magento\Framework\Model\AbstractModel $brand
             */
            $brand = $this->brandsInterfaceFactory->create();
            $this->resource->load($brand, $brandId);

            if (!$brand->getId()) {
                throw new NoSuchEntityException(__('Requested brand doesn\'t exist'));
            }
            $this->instances[$brandId] = $brand;
        }

        return $this->instances[$brandId];
    }

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param  SearchCriteriaInterface $searchCriteria
     * @return \Mage360\Brands\Api\Data\BrandsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /**
         * @var \Mage360\Brands\Api\Data\BrandsSearchResultsInterface $searchResults
         */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /**
         * @var \Mage360\Brands\Model\ResourceModel\Brands\Collection $collection
         */
        $collection = $this->brandsCollectionFactory->create();

        //Add filters from root filter group to the collection
        /**
         * @var FilterGroup $group
         */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        
        /**
         * @var SortOrder $sortOrder
         */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            // set a default sorting order since this method is used constantly in many
            // different blocks
            $field = 'brand_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        /**
         * @var \Mage360\Brands\Api\Data\BrandsInterface[] $brands
         */
        $brands = [];
        /**
         * @var \Mage360\Brands\Model\Brands $brand
         */
        foreach ($collection as $brand) {
            /**
             * @var \Mage360\Brands\Api\Data\BrandsInterface $brandDataObject
             */
            $brandDataObject = $this->brandsInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($brandDataObject, $brand->getData(), BrandsInterface::class);
            $brands[] = $brandDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($brands);
    }

    /**
     * Delete brand.
     *
     * @param  \Mage360\Brands\Api\Data\BrandsInterface $brand
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(BrandsInterface $brand)
    {

        $id = $brand->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($brand);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove Brand %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete Brand by ID.
     *
     * @param  int $brandId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($brandId)
    {
        $brand = $this->getById($brandId);
        return $this->delete($brand);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param  FilterGroup $filterGroup
     * @param  Collection  $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    public function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }
}
