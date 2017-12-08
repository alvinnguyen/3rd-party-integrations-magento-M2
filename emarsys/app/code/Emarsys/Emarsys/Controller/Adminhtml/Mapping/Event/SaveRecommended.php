<?php
/**
 * @category   Emarsys
 * @package    Emarsys_Emarsys
 * @copyright  Copyright (c) 2017 Emarsys. (http://www.emarsys.net/)
 */

namespace Emarsys\Emarsys\Controller\Adminhtml\Mapping\Event;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Emarsys\Emarsys\Model\ResourceModel\Event;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Emarsys\Emarsys\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Emarsys\Emarsys\Model\ResourceModel\Emarsysevents\CollectionFactory;
use Emarsys\Emarsys\Helper\Logs;
use Emarsys\Emarsys\Model\ResourceModel\Emarsysmagentoevents\CollectionFactory as EmarsysmagentoeventsCollectionFactory;
use Emarsys\Emarsys\Model\Api\Api;

class SaveRecommended extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $session;

    /**
     * @var Event
     */
    protected $eventResourceModel;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfigInterface;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * SaveRecommended constructor.
     * @param Context $context
     * @param Event $eventResourceModel
     * @param PageFactory $resultPageFactory
     * @param ScopeConfigInterface $scopeConfigInterface
     * @param StoreManagerInterface $storeManager
     * @param Data $EmarsysHelper
     * @param DateTime $date
     * @param CollectionFactory $EmarsyseventCollection
     * @param Logs $logHelper
     * @param EmarsysmagentoeventsCollectionFactory $magentoEventsCollection
     * @param Api $api
     */
    public function __construct(
        Context $context,
        Event $eventResourceModel,
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfigInterface,
        StoreManagerInterface $storeManager,
        Data $EmarsysHelper,
        DateTime $date,
        CollectionFactory $EmarsyseventCollection,
        Logs $logHelper,
        EmarsysmagentoeventsCollectionFactory $magentoEventsCollection,
        Api $api
    ) {
        parent::__construct($context);
        $this->session = $context->getSession();
        $this->resultPageFactory = $resultPageFactory;
        $this->eventResourceModel = $eventResourceModel;
        $this->magentoEventsCollection = $magentoEventsCollection;
        $this->EmarsyseventCollection = $EmarsyseventCollection;
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->_storeManager = $storeManager;
        $this->EmarsysHelper = $EmarsysHelper;
        $this->_urlInterface = $context->getUrl();
        $this->date = $date;
        $this->logHelper = $logHelper;
        $this->api = $api;
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $eventsCreated = array();
            $session = $this->session;
            $storeId = $session->getStoreId();
            $websiteId = $this->_storeManager->getStore($storeId)->getWebsiteId();
            $logsArray['job_code'] = 'Event Mapping';
            $logsArray['status'] = 'started';
            $logsArray['messages'] = 'Recommended Mapping';
            $logsArray['created_at'] = $this->date->date('Y-m-d H:i:s', time());
            $logsArray['executed_at'] = $this->date->date('Y-m-d H:i:s', time());
            $logsArray['run_mode'] = 'Automatic';
            $logsArray['auto_log'] = 'Complete';
            $logsArray['store_id'] = $storeId;

            if ($this->EmarsysHelper->isEmarsysEnabled($websiteId) == 'false') {
                $logsArray['messages'] = 'Emarsys is not Enabled for this Store';
                $logsArray['created_at'] = $this->date->date('Y-m-d H:i:s', time());
                $logsArray['executed_at'] = $this->date->date('Y-m-d H:i:s', time());
                $logId = $this->logHelper->manualLogs($logsArray);
                $logsArray['id'] = $logId;
                $logsArray['emarsys_info'] = 'Recommended Mapping';
                $logsArray['description'] = 'Recommended Mapping was not Successful';
                $logsArray['action'] = 'Reccommended Mapping';
                $logsArray['message_type'] = 'Error';
                $logsArray['log_action'] = 'True';
                $logsArray['website_id'] = $websiteId;
                $this->logHelper->logs($logsArray);
                $this->messageManager->addErrorMessage('Emarsys is not Enabled for this store');
                return $resultRedirect->setRefererOrBaseUrl();
            }
            $logId = $this->logHelper->manualLogs($logsArray);
            $logsArray['id'] = $logId;
            $logsArray['emarsys_info'] = 'Recommended Mapping';
            $logsArray['description'] = 'Recommended Mapping In progress';
            $logsArray['action'] = 'Schame Updated';
            $logsArray['message_type'] = 'Success';
            $logsArray['log_action'] = 'True';
            $logsArray['website_id'] = $websiteId;
            $this->logHelper->logs($logsArray);
            $this->EmarsysHelper->importEvents($logId);
            $emarsysEvents = $this->EmarsyseventCollection->create();
            $this->api->setWebsiteId($websiteId);
            $dbEvents = [];
            foreach ($emarsysEvents as $emarsysEvent) {
                $dbEvents[] = $emarsysEvent->getEmarsysEvent();
            }
            $hasNewEvents = false;
            $magentoEvents = $this->magentoEventsCollection->create();
            foreach ($magentoEvents as $magentoEvent) {
                if ($this->EmarsysHelper->isReadonlyMagentoEventId($magentoEvent->getId())) {
                    continue;
                }
                $magentoEventname = $magentoEvent->getMagentoEvent();
                $emarsysEventname = trim(str_replace(" ", "_", strtolower($magentoEventname)));
                if (!in_array($emarsysEventname, $dbEvents)) {
                    $eventsCreated[] = $data['name'] = $emarsysEventname;
                    $hasNewEvents = true;
                    $this->api->sendRequest('POST', 'event', $data);
                }
            }
            if ($eventsCreated && $logId) {
                $logsArray['id'] = $logId;
                $logsArray['emarsys_info'] = 'Recommended Mapping';
                $logsArray['description'] = 'Events Created ' . implode(",", $eventsCreated);
                $logsArray['action'] = 'Recommended Mapping';
                $logsArray['message_type'] = 'Success';
                $logsArray['log_action'] = 'True';
                $logsArray['website_id'] = $websiteId;
                $this->logHelper->logs($logsArray);
            }
            if ($hasNewEvents) {
                $this->EmarsysHelper->importEvents($logId);
            }
            $this->messageManager->addSuccessMessage("Recommended Emarsys Events Created Successfully!");
            $this->messageManager->addSuccessMessage('Important: Hit "Save Mapping" to complete the mapping!');

            return $resultRedirect->setUrl($this->_urlInterface->getUrl("emarsys_emarsys/mapping_event", ["store" => $storeId, "recommended" => 1, "limit" => 200]));
        } catch (\Exception $e) {
            $logsArray['id'] = $logId;
            $logsArray['emarsys_info'] = 'Recommended Mapping';
            $logsArray['description'] = $e->getMessage();
            $logsArray['action'] = 'Recommended Mapping not successful';
            $logsArray['message_type'] = 'Error';
            $logsArray['log_action'] = 'True';
            $logsArray['website_id'] = $websiteId;
            $this->logHelper->logs($logsArray);
            $this->messageManager->addErrorMessage('Error occurred while Recommended Mapping' . $e->getMessage());
        }

        return $resultRedirect->setRefererOrBaseUrl();
    }
}
