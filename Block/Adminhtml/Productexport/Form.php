<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2017 Emarsys. (http://www.emarsys.net/)
 */

namespace Emarsys\Emarsys\Block\Adminhtml\Productexport;

use Magento\Backend\Block\Widget\Context;
use Emarsys\Emarsys\Helper\Data as EmarsysHelper;

/**
 * Class Form
 *
 * @package Emarsys\Emarsys\Block\Adminhtml\Productexport
 */
class Form extends \Emarsys\Emarsys\Block\Adminhtml\Export\Form
{
    /**
     * Form constructor.
     * @param Context $context
     * @param array $data
     * @param EmarsysHelper $emarsysHelper
     */
    public function __construct(
        Context $context,
        array $data = [],
        EmarsysHelper $emarsysHelper
    ) {
        parent::__construct($context, $data, $emarsysHelper);
        $this->setId('productExportForm');
    }
}
