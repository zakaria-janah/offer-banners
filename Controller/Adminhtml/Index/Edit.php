<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Controller\Adminhtml\Index;

use Dnd\Offers\Api\OfferRepositoryInterface;
use Dnd\Offers\Model\Offer;
use Dnd\Offers\Model\OfferFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;

/**
 * Class Edit
 */
class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dnd_Offers::offers';

    /** @var Registry */
    private Registry $coreRegistry;

    /** @var DataPersistorInterface */
    private DataPersistorInterface $dataPersistor;

    /** @var OfferRepositoryInterface */
    private OfferRepositoryInterface $offerRepository;

    /** @var OfferFactory */
    private OfferFactory $offerFactory;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param OfferRepositoryInterface $offerRepository
     * @param OfferFactory $offerFactory
     * @param DataPersistorInterface $dataPersistor
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        OfferRepositoryInterface $offerRepository,
        OfferFactory $offerFactory,
        DataPersistorInterface $dataPersistor,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->dataPersistor = $dataPersistor;
        $this->offerRepository = $offerRepository;
        $this->offerFactory = $offerFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface|ResponseInterface
    {
        $offerId = (int)$this->getRequest()->getParam('id');
        if ($offerId) {
            try {
                $model = $this->offerRepository->getById($offerId);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This offer no longer exists.'));

                return $this->getRedirect('*/*/index');
            }
        } else {
            /** @var Offer $model */
            $model = $this->offerFactory->create();
        }

        // set entered data if was error when we do save
        $data = $this->dataPersistor->get(Offer::PERSIST_NAME);
        if (!empty($data) && !$model->getId()) {
            $model->addData($data);
        }
        $this->coreRegistry->register(Offer::PERSIST_NAME, $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->updateTitles($resultPage, $model);

        return $resultPage;
    }

    /**
     * @param string $path
     * @param array $params
     * @return Redirect
     */
    private function getRedirect(string $path = '', array $params = []): Redirect
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($path) {
            $redirect->setPath($path, $params);
        } else {
            $redirect->setRefererUrl();
        }

        return $redirect;
    }

    /**
     * @param Page $page
     * @param Offer $model
     * @return void
     */
    private function updateTitles(Page $page, Offer $model): void
    {
        $title = $model->getId() ?
            __('Edit Offer # %1', $model->getId())
            : __('New Offer');
        $page->addBreadcrumb($title, $title);
        $page->getConfig()->getTitle()->prepend($title);
    }
}
