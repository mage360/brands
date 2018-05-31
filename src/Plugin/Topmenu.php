<?php
namespace Mage360\Brands\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Mage360\Brands\Model\Brands;

class Topmenu
{
    private $nodeFactory;
    private $storeManager;
    private $scopeConfig;
    private $urlInterface;

    public function __construct(
        NodeFactory $nodeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->urlInterface = $urlInterface;
    }
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject
    ) {
        // If not enable return
        $is_enable = $this->scopeConfig->getValue(
            Brands::CONFIG_ADD_MAINMENU,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (empty($is_enable)) {
            return;
        }

        $urlPath = $this->scopeConfig->getValue(
            Brands::CONFIG_MAIN_URL_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (empty($urlPath)) {
            $urlPath = "brands";
        }
        // Check if current url is shop by brand url
        $url = $this->urlInterface->getCurrentUrl();
        if (strpos($url, $urlPath)=== false) {
            $isActive = false;
        } else {
            $isActive = true;
        }
        // Add custom link to the menu node.
        $node = $this->nodeFactory->create(
            [
                'data' => [
                    'name' =>  $this->scopeConfig->getValue(
                        Brands::CONFIG_MENU_TITLE,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'id' => "mage360_brands",
                    'url' => $this->urlInterface->getUrl($urlPath),
                    'has_active' => false,
                    'is_active' => $isActive
                ],
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $subject->getMenu()->addChild($node);
    }
}
