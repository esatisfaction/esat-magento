<?php

// Initialize
$installer = $this;
$installer->startSetup();

// Prepare
$table = $installer->getConnection()
    ->newTable($installer->getTable('esatisfaction_item'))
    ->addColumn('order_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ], 'OrderItemId')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary' => true,
    ], 'OrderId')
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, [
        'nullable' => false,
    ], 'ItemId');

// Create table
$installer->getConnection()->createTable($table);

// Finalize
$installer->endSetup();
