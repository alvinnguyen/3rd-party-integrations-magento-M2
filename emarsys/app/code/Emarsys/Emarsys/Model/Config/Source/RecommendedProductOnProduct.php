<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2016 Kensium Solution Pvt.Ltd. (http://www.kensiumsolutions.com/)
 */
namespace Emarsys\Emarsys\Model\Config\Source;

class RecommendedProductOnProduct
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '||', 'label' => 'Please Select'],
            ['value' => 'RELATED||related-template', 'label' => 'Related'],
            ['value' => 'ALSO_BOUGHT||alsobought-template', 'label' => 'Also Bought'],
            ['value' => 'PERSONAL||personal-template', 'label' => 'Personal']
        ];
    }
}
