<?php

namespace Esat\Esatisfaction\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('esatisfaction_item')) {
            $table = $installer->getConnection()->newTable($installer->getTable('esatisfaction_item'))
            ->addColumn('order_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true,
                ], 'OrderItemId')
            ->addColumn('order_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'primary'   => true,
                ], 'OrderId')
            ->addColumn('item_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
                'nullable'  => false,
                ], 'ItemId');

            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
