<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2017 Emarsys. (http://www.emarsys.net/)
 */
namespace Emarsys\Emarsys\Model\Config\Source;

/**
 * Class Smartinsightfrequency
 * @package Emarsys\Emarsys\Model\Config\Source
 */
class Smartinsightfrequency implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'Hourly', 'label' => __('Hourly')],
            ['value' => 'Daily', 'label' => __('Daily')]

        ];
    }
}
