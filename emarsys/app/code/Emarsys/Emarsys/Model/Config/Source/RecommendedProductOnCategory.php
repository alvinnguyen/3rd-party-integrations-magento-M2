<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2016 Kensium Solution Pvt.Ltd. (http://www.kensiumsolutions.com/)
 */
namespace Emarsys\Emarsys\Model\Config\Source;

class RecommendedProductOnCategory
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '||', 'label' => 'Please Select'],
            ['value' => 'CATEGORY||category-template', 'label' => 'Category'],
            ['value' => 'DEPARTMENT||simple-tmpl', 'label' => 'Department'],
            ['value' => 'PERSONAL||personal-template', 'label' => 'Personal'],
            ['value' => 'POPULAR||popular-template', 'label' => 'Popular'],
        ];
    }
}
