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

namespace Mage360\Brands\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Mage360\Brands\Api\BrandsRepositoryInterface;

abstract class Brands extends Action
{

    /**
     * @var string
     */
    const ACTION_RESOURCE = 'Mage360_Brands::brands';
    /**
     * Storelocator factory
     *
     * @var BrandsRepositoryInterface
     */
    public $brandsRepository;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * date filter
     *
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    public $dateFilter;

    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @param Registry                  $registry
     * @param BrandsRepositoryInterface $brandsRepository
     * @param PageFactory               $resultPageFactory
     * @param Date                      $dateFilter
     * @param Context                   $context
     */
    public function __construct(
        Registry $registry,
        BrandsRepositoryInterface $brandsRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context
    ) {
        $this->coreRegistry      = $registry;
        $this->brandsRepository  = $brandsRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->dateFilter        = $dateFilter;
        parent::__construct($context);
    }
}
