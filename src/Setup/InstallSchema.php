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

namespace Mage360\Brands\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
	// @codingStandardsIgnoreStart 
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
    // @codingStandardsIgnoreEnd
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mage360_brands')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mage360_brands'));
            $table->addColumn(
                'brand_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Brand ID'
            )
                ->addColumn(
                    'attribute_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['default' => null , 'unique' => true],
                    'attribute_id'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'Brand Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable'  => false,],
                    'Brand description'
                )
                ->addColumn(
                    'url_key',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'url key'
                )
                ->addColumn(
                    'logo_path',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'logo path'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['default' => 0],
                    'Sort Order'
                )
                ->addColumn(
                    'is_active',
                    Table::TYPE_INTEGER,
                    null,
                    ['default' => 0],
                    'is active'
                )
                ->addColumn(
                    'is_featured',
                    Table::TYPE_INTEGER,
                    null,
                    ['default' => 0],
                    'Is featured'
                )
                ->addColumn(
                    'seo_title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'Page title'
                )
                ->addColumn(
                    'seo_desc',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'Meta description'
                )
                ->addColumn(
                    'seo_keyword',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'Meta keywords'
                )
                ->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Update at'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->setComment('Brands');

            $installer->getConnection()->createTable($table);
            
            $installer->getConnection()->addIndex(
                $installer->getTable('mage360_brands'),
                $setup->getIdxName(
                    $installer->getTable('mage360_brands'),
                    ['name','url_key'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                [
                    'name',
                    'url_key',
                    'logo_path'
                ],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
            
            $installer->endSetup();
        }
    }
}
