<?php
declare(strict_types=1);

namespace Mage360\Brands\Block\Adminhtml\Brands\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Mage360\Brands\Api\BrandsRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Generic
{
    /**
     * @var Context
     */
    public $context;

    /**
     * @var BrandsRepositoryInterface
     */
    public $brandsRepository;

    /**
     * @param Context                   $context
     * @param BrandsRepositoryInterface $brandsRepository
     */
    public function __construct(
        Context $context,
        BrandsRepositoryInterface $brandsRepository
    ) {
        $this->context = $context;
        $this->brandsRepository = $brandsRepository;
    }

    /**
     * Return Brand page ID
     *
     * @return int|null
     */
    public function getBrandId()
    {
        try {
            return $this->brandsRepository->getById(
                $this->context->getRequest()->getParam('brand_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param  string $route
     * @param  array  $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
