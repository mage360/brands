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
namespace Mage360\Brands\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Collection\Db;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Mage360\Brands\Api\Data\BrandsInterface;
use Mage360\Brands\Model\ResourceModel\Brands as BrandsResourceModel;
use Mage360\Brands\Model\Source\AbstractSource;
use Mage360\Brands\Model\Routing\RoutableInterface;

class Brands extends AbstractModel implements BrandsInterface, RoutableInterface
{

    /**
     * @var string
     */
    const CONFIG_BREADCRUMB = "mage360_brands/brands_general/breadcrumbs";

    /**
     * @var string
     */
    const CONFIG_MENU_TITLE = "mage360_brands/brands_url/menu_name";

    /**
     * @var string
     */
    const CONFIG_ADD_MAINMENU = "mage360_brands/brands_url/add_main_menu";

    /**
     * @var string
     */
    const CONFIG_MAIN_URL_KEY = "mage360_brands/brands_url/brand_url_key";

    /**
     * @var string
     */
    const CONFIG_TITLE = "mage360_brands/brands_seo/meta_title";

    /**
     * @var string
     */
    const CONFIG_META_DESC = "mage360_brands/brands_seo/meta_description";

    /**
     * @var string
     */
    const CONFIG_META_KEYWORD = "mage360_brands/brands_seo/meta_keyword";

    /**
     * @var int
     */
    const STATUS_ENABLED = 1;
    /**
     * @var int
     */
    const STATUS_DISABLED = 0;

    /**
     * Url Prefix
     *
     * @var string
     */
    const URL_PREFIX = 'brand';

    /**
     * Url extension
     *
     * @var string
     */
    const URL_EXT = '.html';
    
    /**
     * Cache tag
     */
    const CACHE_TAG = 'mage360_brands_brands';
    
    
    /**
     * Prefix of model events names
     *
     * @var string
     */
    public $_eventPrefix = 'mage360_brands_brands';

    /**
     * filter model
     *
     * @var \Magento\Framework\Filter\FilterManager
     */
    public $filter;

    /**
     * @var UploaderPool
     */
    public $uploaderPool;

    /**
     * @var \Mage360\Brands\Model\Output
     */
    public $outputProcessor;

    /**
     * @var AbstractSource[]
     */
    public $optionProviders;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @param Context               $context
     * @param Registry              $registry
     * @param Output                $outputProcessor
     * @param UploaderPool          $uploaderPool
     * @param FilterManager         $filter
     * @param array                 $optionProviders
     * @param array                 $data
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Output $outputProcessor,
        UploaderPool $uploaderPool,
        FilterManager $filter,
        StoreManagerInterface $storeManager,
        array $optionProviders = [],
        array $data = [],
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null
    ) {
        $this->outputProcessor = $outputProcessor;
        $this->uploaderPool    = $uploaderPool;
        $this->filter          = $filter;
        $this->optionProviders = $optionProviders;
        $this->storeManager    = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(BrandsResourceModel::class);
    }

    /**
     * set name
     *
     * @param  $name
     * @return BrandsInterface
     */
    public function setName($name)
    {
        return $this->setData(BrandsInterface::NAME, $name);
    }

    /**
     * set description
     *
     * @param  $description
     * @return BrandsInterface
     */
    public function setDescription($description)
    {
        return $this->setData(BrandsInterface::DESCRIPTION, $description);
    }

    /**
     * set Is Active
     *
     * @param  $isActive
     * @return BrandsInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(BrandsInterface::IS_ACTIVE, $isActive);
    }

    /**
     * set Is Featured
     *
     * @param  $isFeatured
     * @return BrandsInterface
     */
    public function setIsFeatured($isFeatured)
    {
        return $this->setData(BrandsInterface::IS_FEATURED, $isFeatured);
    }

    /**
     * set Url Key
     *
     * @param  $urlKey
     * @return BrandsInterface
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(BrandsInterface::URL_KEY, $urlKey);
    }

    /**
     * set Attribute id
     *
     * @param  $attributeId
     * @return BrandsInterface
     */
    public function setAttributeId($attributeId)
    {
        return $this->setData(BrandsInterface::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * set Logo Path
     *
     * @param  $logPath
     * @return BrandsInterface
     */
    public function setLogoPath($logPath)
    {
        return $this->setData(BrandsInterface::LOGO_PATH, $logPath);
    }

    /**
     * set seo title
     *
     * @param  $seoTitle
     * @return BrandsInterface
     */
    public function setSeoTitle($seoTitle)
    {
        return $this->setData(BrandsInterface::SEO_TITLE, $seoTitle);
    }

    /**
     * set seo desc
     *
     * @param  $seoDesc
     * @return BrandsInterface
     */
    public function setSeoDesc($seoDesc)
    {
        return $this->setData(BrandsInterface::SEO_DESC, $seoDesc);
    }

    /**
     * set seo keyword
     *
     * @param  $seoKeyword
     * @return BrandsInterface
     */
    public function setSeoKeyword($seoKeyword)
    {
        return $this->setData(BrandsInterface::SEO_KEYWORD, $seoKeyword);
    }

    /**
     * set created at
     *
     * @param  $createdAt
     * @return BrandsInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(BrandsInterface::CREATED_AT, $createdAt);
    }

    /**
     * set updated at
     *
     * @param  $updatedAt
     * @return BrandsInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(BrandsInterface::UPDATED_AT, $updatedAt);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(BrandsInterface::NAME);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(BrandsInterface::DESCRIPTION);
    }

    /**
     * Get Is Active
     *
     * @return Integer
     */
    public function getIsActive()
    {
        return $this->getData(BrandsInterface::IS_ACTIVE);
    }

    /**
     * Get Is Featured
     *
     * @return Integer
     */
    public function getIsFeatured()
    {
        return $this->getData(BrandsInterface::IS_FEATURED);
    }

    /**
     * Get Url Key
     *
     * @return string
     */
    public function getUrlKey()
    {
        return $this->getData(BrandsInterface::URL_KEY);
    }

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl()
    {
        $url_key = $this->getUrlKey();

        return $this->storeManager->getStore()->getUrl(self::URL_PREFIX.'/'.$url_key.self::URL_EXT);
    }

    /**
     * Get Attribute id
     *
     * @return Integer
     */
    public function getAttributeId()
    {
        return $this->getData(BrandsInterface::ATTRIBUTE_ID);
    }

    /**
     * Get Logo Path
     *
     * @return string
     */
    public function getLogoPath()
    {
        return $this->getData(BrandsInterface::LOGO_PATH);
    }

    /**
     *  Get Logo Path url
     *
     * @return bool|string
     * @throws LocalizedException
     */
    public function getLogoPathUrl()
    {
        $url = false;
        $image = $this->getLogoPath();
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $url = $uploader->getBaseUrl().$uploader->getBasePath().$image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    /**
     * Get seo title
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->getData(BrandsInterface::SEO_TITLE);
    }

    /**
     * Get seo desc
     *
     * @return string
     */
    public function getSeoDesc()
    {
        return $this->getData(BrandsInterface::SEO_DESC);
    }

    /**
     * Get seo keyword
     *
     * @return string
     */
    public function getSeoKeyword()
    {
        return $this->getData(BrandsInterface::SEO_KEYWORD);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(BrandsInterface::CREATED_AT);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(BrandsInterface::UPDATED_AT);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
