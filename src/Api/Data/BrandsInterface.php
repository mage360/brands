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
namespace Mage360\Brands\Api\Data;

/**
 * @api
 */
interface BrandsInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const STORE_ID               = 'store_id';
    const BRAND_ID               = 'brand_id';
    const NAME                = 'name';
    const DESCRIPTION         = 'description';
    const IS_ACTIVE           = 'is_active';
    const IS_FEATURED         = 'is_featured';
    const URL_KEY             = 'url_key';
    const ATTRIBUTE_ID        = 'attribute_id';
    const LOGO_PATH           = 'logo_path';
    const SEO_TITLE           = 'seo_title';
    const SEO_DESC            = 'seo_desc';
    const SEO_KEYWORD         = 'seo_keyword';
    const UPDATED_AT          = 'updated_at';
    const CREATED_AT          = 'created_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get Logo Path
     *
     * @return string
     */
    public function getLogoPath();

    /**
     * Get Url Key
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Get Is Active
     *
     * @return bool|int
     */
    public function getIsActive();

    /**
     * Get Is Featured
     *
     * @return bool|int
     */
    public function getIsFeatured();

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Get Seo title
     *
     * @return string
     */
    public function getSeoTitle();

    /**
     * Get Seo desc
     *
     * @return string
     */
    public function getSeoDesc();

    /**
     * Get Seo keyword
     *
     * @return string
     */
    public function getSeoKeyword();

    /**
     * set id
     *
     * @param  $id
     * @return BrandsInterface
     */
    public function setId($id);

    /**
     * set name
     *
     * @param  $name
     * @return BrandsInterface
     */
    public function setName($name);

    /**
     * Set Logo Path
     *
     * @param  $logoPath
     * @return BrandsInterface
     */
    public function setLogoPath($logoPath);

    /**
     * Set Url Key
     *
     * @param  $urlKey
     * @return BrandsInterface
     */
    public function setUrlKey($urlKey);

    /**
     * Set Is Active
     *
     * @param  $isActive
     * @return BrandsInterface
     */
    public function setIsActive($isActive);

    /**
     * Set Is Featured
     *
     * @param  $isFeatured
     * @return BrandsInterface
     */
    public function setIsFeatured($isFeatured);

    /**
     * Set Description
     *
     * @param  $description
     * @return BrandsInterface
     */
    public function setDescription($description);

    /**
     * Get Seo title
     *
     * @param  $seoTitle
     * @return BrandsInterface
     */
    public function setSeoTitle($seoTitle);

    /**
     * Get Seo desc
     *
     * @param  $seoDesc
     * @return BrandsInterface
     */
    public function setSeoDesc($seoDesc);

    /**
     * Get Seo keyword
     *
     * @param  $seoKeyword
     * @return BrandsInterface
     */
    public function setSeoKeyword($seoKeyword);
}
