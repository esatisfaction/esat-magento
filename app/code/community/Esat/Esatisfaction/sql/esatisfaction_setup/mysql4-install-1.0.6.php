<?php 

$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'esat_icon', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable'  => false,
    'length'    => 255,
    'after'     => null, // column name to insert new column after
    'comment'   => 'Esatisfaction Icon'
    ));   
$installer->endSetup();
?>