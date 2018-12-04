<?php
$installer = $this;
 
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('esatisfaction_item'))
    ->addColumn('order_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'OrderItemId')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'   => true,
        ), 'OrderId')
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => false,
        ), 'ItemId');
		
$installer->getConnection()->createTable($table);

$installer->endSetup();

?>