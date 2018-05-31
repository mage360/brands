<?php
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
namespace Mage360\Brands\Controller\Adminhtml\Brands;

use Mage360\Brands\Controller\Adminhtml\Brands as BrandsController;
use Mage360\Brands\Controller\RegistryConstants;

class Edit extends BrandsController
{

    /**
     * Initialize current Brand and set it in the registry.
     *
     * @return int
     */
    private function _initBrand()
    {
        $brandId = $this->getRequest()->getParam('brand_id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_BRAND_ID, $brandId);

        return $brandId;
    }

    /**
     * Stores list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $brandId = $this->_initBrand();
        
        /**
         * @var \Magento\Backend\Model\View\Result\Page $resultPage
        */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Mage360_Brands::brands');
        $resultPage->getConfig()->getTitle()->prepend(__('Brands'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'), $this->getUrl('Brands/brands'));

        if ($brandId === null) {
             $resultPage->addBreadcrumb(__('New Brand'), __('New Brand'));
             $resultPage->getConfig()->getTitle()->prepend(__('New Brand'));
        } else {
             $resultPage->addBreadcrumb(__('Edit Brand'), __('Edit Brand'));

             $resultPage->getConfig()->getTitle()->prepend(
                 $this->brandsRepository->getById($brandId)->getName()
             );
        }
        return $resultPage;
    }
}
