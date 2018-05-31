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
namespace Mage360\Brands\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Mage360\Brands\Api\Data\BrandsInterface;

/**
 * @api
 */
interface BrandsRepositoryInterface
{
    /**
     * Save page.
     *
     * @param  BrandsInterface $brands
     * @return BrandsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(BrandsInterface $brand);

    /**
     * Retrieve Store.
     *
     * @param  int $brandId
     * @return BrandsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($brandId);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param  SearchCriteriaInterface $searchCriteria
     * @return \Mage360\Storelocator\Api\Data\StorelocatorSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete store.
     *
     * @param  BrandsInterface $brand
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(BrandsInterface $brand);

    /**
     * Delete Store by ID.
     *
     * @param  int $storeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($storeId);
}
