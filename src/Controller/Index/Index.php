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

namespace Mage360\Brands\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Mage360\Brands\Model\Brands;

class Index extends Action
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     *
     * @param Context              $context
     * @param PageFactory          $resultPageFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfig
    ) {

        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $resultPage  = $this->resultPageFactory->create();

        /*
         * Meta Keywords
         */
        $resultPage->getConfig()->setKeywords(
            $this->scopeConfig->getValue(Brands::CONFIG_META_KEYWORD, ScopeInterface::SCOPE_STORE)
        );

        /*
        * Meta Description
        */
        $resultPage->getConfig()->setDescription(
            $this->scopeConfig->getValue(Brands::CONFIG_META_DESC, ScopeInterface::SCOPE_STORE)
        );

        /*
         * Page Title
         */
        $resultPage->getConfig()->getTitle()->set(
            $this->scopeConfig->getValue(Brands::CONFIG_TITLE, ScopeInterface::SCOPE_STORE)
        );

        if ($this->scopeConfig->isSetFlag(Brands::CONFIG_BREADCRUMB, ScopeInterface::SCOPE_STORE)) {

            /**
             * @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbsBlock
            */
            $breadcrumbsBlock = $resultPage->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbsBlock) {
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label'    => __('Home'),
                        'link'     => $this->_url->getUrl('')
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'brands',
                    [
                        'label'    => $this->scopeConfig->getValue(
                            Brands::CONFIG_MENU_TITLE,
                            ScopeInterface::SCOPE_STORE
                        ),
                    ]
                );
            }
        }

        return $resultPage;
    }
}
