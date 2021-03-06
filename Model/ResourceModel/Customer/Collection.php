<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2017 Emarsys. (http://www.emarsys.net/)
 */

namespace Emarsys\Emarsys\Model\ResourceModel\Customer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Emarsys\Emarsys\Model\ResourceModel\Customer
 */
class Collection extends AbstractCollection
{
    /**
     * Initializes collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Emarsys\Emarsys\Model\Customer', 'Emarsys\Emarsys\Model\ResourceModel\Customer');
    }
}
