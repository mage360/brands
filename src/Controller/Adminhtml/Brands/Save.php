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
namespace Mage360\Brands\Controller\Adminhtml\Brands;

use Magento\Backend\Model\Session;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Mage360\Brands\Api\BrandsRepositoryInterface;
use Mage360\Brands\Api\Data\BrandsInterface;
use Mage360\Brands\Api\Data\BrandsInterfaceFactory;
use Mage360\Brands\Controller\Adminhtml\Brands as BrandsController;
use Mage360\Brands\Model\Brands as BrandsModel;
use Mage360\Brands\Model\Uploader;
use Mage360\Brands\Model\UploaderPool;
use Magento\UrlRewrite\Model\UrlRewrite as BaseUrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewriteService;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mage360\Brands\Block\Brands;

class Save extends BrandsController
{
    /**
     * @var DataObjectProcessor
     */
    public $dataObjectProcessor;

    /**
     * @var DataObjectHelper
     */
    public $dataObjectHelper;

    /**
     * @var UploaderPool
     */
    public $uploaderPool;

    /**
     * @var BaseUrlRewrite
     */
    public $urlRewrite;

    /**
     * Url rewrite service
     *
     * @var $urlRewriteService
     */
    public $urlRewriteService;

    /**
     * Url finder
     *
     * @var UrlFinderInterface
     */
    public $urlFinder;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    public $storeManager;

    /**
     * Configuration
     *
     * @var Brands
     */
    public $brandsConfig;

    /**
     * BrandsInterfaceFactory
     *
     * @var Brands
     */
    public $brandsFactory;

    /**
     * @var UrlRewriteFactory
     */
    public $urlRewriteFactory;

    private $urlPrefix;

    private $urlExtension;
    /**
     * @param Registry                  $registry
     * @param BrandsRepositoryInterface $brandsRepository
     * @param PageFactory               $resultPageFactory
     * @param Date                      $dateFilter
     * @param Context                   $context
     * @param BrandsInterfaceFactory    $brandsFactory
     * @param DataObjectProcessor       $dataObjectProcessor
     * @param DataObjectHelper          $dataObjectHelper
     * @param UploaderPool              $uploaderPool
     */
    public function __construct(
        Registry $registry,
        BrandsRepositoryInterface $brandsRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context,
        BaseUrlRewrite $urlRewrite,
        UrlRewriteService $urlRewriteService,
        UrlFinderInterface $urlFinder,
        StoreManagerInterface $storeManager,
        UrlRewriteFactory $urlRewriteFactory,
        BrandsInterfaceFactory $brandsFactory,
        Brands $brandsConfig,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        UploaderPool $uploaderPool
    ) {
        $this->urlRewrite = $urlRewrite;
        $this->urlRewriteService = $urlRewriteService;
        $this->urlFinder = $urlFinder;
        $this->storeManager = $storeManager;
        $this->brandsConfig = $brandsConfig;
        $this->brandsFactory = $brandsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->uploaderPool = $uploaderPool;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->urlPrefix = BrandsModel::URL_PREFIX;
        $this->urlExtension = BrandsModel::URL_EXT;

        parent::__construct($registry, $brandsRepository, $resultPageFactory, $dateFilter, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /**
         * @var \Mage360\Brands\Api\Data\BrandsInterface $brands
         */
        $brand = null;
        $data = $this->getRequest()->getPostValue();
        $id = !empty($data['brand_id']) ? $data['brand_id'] : null;
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            if ($id) {
                $brand = $this->brandsRepository->getById((int)$id);
            } else {
                unset($data['brand_id']);
                $brand = $this->brandsFactory->create();
            }
            $image = $this->getUploader('image')->uploadFileAndGetName('logo_path', $data);
            $data['logo_path'] = $image;

            $this->dataObjectHelper->populateWithArray($brand, $data, BrandsInterface::class);
            $this->brandsRepository->save($brand);

            if (!empty($data["url_key"])) {
                $this->saveUrlRewrite($data["url_key"], $brand->getId(), $this->storeManager->getStore()->getId());
            }

            $this->messageManager->addSuccessMessage(__('You saved the Brand'));
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('brands/brands/edit', ['brand_id' => $brand->getId()]);
            } else {
                $resultRedirect->setPath('brands/brands');
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            if ($brand != null) {
                $this->storeBrandsDataToSession(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $brand,
                        BrandsInterface::class
                    )
                );
            }
            $resultRedirect->setPath('brands/brands/edit', ['brand_id' => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            if ($brand != null) {
                $this->storeBrandsDataToSession(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $brand,
                        BrandsInterface::class
                    )
                );
            }
            $resultRedirect->setPath('brands/brands/edit', ['brand_id' => $id]);
        }
        return $resultRedirect;
    }

    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    private function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }

    /**
     * @param $brandsData
     */
    private function storeBrandsDataToSession($brandsData)
    {
        $this->_getSession()->setMage360BrandsStoresData($brandsData);
    }

    /**
     * Saves the url rewrite for that specific store
     *
     * @param  $link string
     * @param  $id int
     * @param  $storeIds string
     * @return void
     */
    private function saveUrlRewrite($link, $id, $storeId)
    {

        $getCustomUrlRewrite = $this->urlPrefix . "/" . $link.$this->urlExtension;

        $brandId = $this->urlPrefix . "-" . $id;
        
        $filterData = [
            UrlRewriteService::STORE_ID => $storeId,
            UrlRewriteService::REQUEST_PATH => $getCustomUrlRewrite,
            UrlRewriteService::ENTITY_ID => $id,

        ];

        // check if there is an entity with same url and same id
        $rewriteFinder = $this->urlFinder->findOneByData($filterData);

        // if there is then do nothing, otherwise proceed
        if ($rewriteFinder === null) {
            // check maybe there is an old url with this target path and delete it
            $filterDataOldUrl = [
                UrlRewriteService::STORE_ID => $storeId,
                UrlRewriteService::REQUEST_PATH => $getCustomUrlRewrite,
            ];
            $rewriteFinderOldUrl = $this->urlFinder->findOneByData($filterDataOldUrl);

            if ($rewriteFinderOldUrl !== null) {
                $this->urlRewrite->load($rewriteFinderOldUrl->getUrlRewriteId())->delete();
            }

            // check maybe there is an old id with different url, in this case load the id and update the url
            $filterDataOldId = [
                UrlRewriteService::STORE_ID => $storeId,
                UrlRewriteService::ENTITY_TYPE => $brandId,
                UrlRewriteService::ENTITY_ID => $id
            ];
            $rewriteFinderOldId = $this->urlFinder->findOneByData($filterDataOldId);

            if ($rewriteFinderOldId !== null) {
                $this->urlRewriteFactory->create()->load($rewriteFinderOldId->getUrlRewriteId())
                    ->setRequestPath($getCustomUrlRewrite)
                    ->save();
            }
			else
			{
                // now we can save
                $this->urlRewriteFactory->create()
                    ->setStoreId($storeId)
                    ->setIdPath(rand(1, 100000))
                    ->setRequestPath($getCustomUrlRewrite)
                    ->setTargetPath("brands/view/index")
                    ->setEntityType($brandId)
                    ->setEntityId($id)
                    ->setIsAutogenerated(0)
                    ->save();
            }
        }
    }
}
