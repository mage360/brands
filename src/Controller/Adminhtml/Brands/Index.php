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

use Mage360\Brands\Controller\Adminhtml\Brands as BrandsController;

class Index extends BrandsController
{

    /**
     * Stores list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
    
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        
        $resultPage->setActiveMenu('Mage360_Brands::brands');
        $resultPage->getConfig()->getTitle()->prepend(__('Brands'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));

        return $resultPage;
    }
}
