<?php
namespace Mage360\Brands\Model\ResourceModel;

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
 
class Brands extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mage360_brands', 'brand_id');
    }
}
