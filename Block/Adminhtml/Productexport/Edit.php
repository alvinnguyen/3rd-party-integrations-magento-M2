<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2017 Emarsys. (http://www.emarsys.net/)
 */

namespace Emarsys\Emarsys\Block\Adminhtml\Productexport;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class Edit
 *
 * @package Emarsys\Emarsys\Block\Adminhtml\Productexport
 */
class Edit extends \Emarsys\Emarsys\Block\Adminhtml\Export\Edit
{
    /**
     * Support edit block
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_productexport';
        $storeId = $this->getRequest->getParam('store');
        $storeId = $this->emarsysHelper->getFirstStoreIdOfWebsiteByStoreId($storeId);
        $url = $this->getUrl("emarsys_emarsys/productexport/productExport", ["storeId" => $storeId]);

        parent::_construct();

        $this->buttonList->remove('back');
        $this->buttonList->remove('save');
        $this->buttonList->remove('reset');
        if (isset($storeId)) {
            $this->buttonList->add(
                'showreport',
                [
                    'label' => __("Export"),
                    'class' => 'save primary',
                    'onclick' => "bulkProductExportData('" . $url . "')",
                ]
            );
        }
    }
}
