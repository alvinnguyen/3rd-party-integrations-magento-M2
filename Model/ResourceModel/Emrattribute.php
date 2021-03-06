<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2019 Emarsys. (http://www.emarsys.net/)
 */
namespace Emarsys\Emarsys\Model\ResourceModel;

/**
 * Class Product
 * @package Emarsys\Emarsys\Model\ResourceModel
 */
class Emrattribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('emarsys_emarsys_product_attributes', 'id');
    }

}
