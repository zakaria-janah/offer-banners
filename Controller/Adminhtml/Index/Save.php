<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Controller\Adminhtml\Index;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Api\OfferRepositoryInterface;
use Dnd\Offers\Model\Offer;
use Dnd\Offers\Model\OfferFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dnd_Offers::offers';

    /** @var DataPersistorInterface */
    private DataPersistorInterface $dataPersistor;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var OfferRepositoryInterface */
    private OfferRepositoryInterface $offerRepository;

    /** @var OfferFactory */
    private OfferFactory $offerFactory;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param OfferRepositoryInterface $offerRepository
     * @param OfferFactory $offerFactory
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        OfferRepositoryInterface $offerRepository,
        OfferFactory $offerFactory,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
        $this->offerFactory = $offerFactory;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute(): ResponseInterface|ResultInterface
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($data = $this->getRequest()->getPostValue()) {
            /** @var Offer $model */
            $model = $this->offerFactory->create();

            try {
                if ($offerId = (int) $this->getRequest()->getParam('id')) {
                    $model = $this->offerRepository->getById($offerId);
                }

                $data = $this->prepareData($data);
                $model->setData($data);
                $this->offerRepository->save($model);

                $this->messageManager->addSuccessMessage(__('The offer was successfully saved.'));
                $this->dataPersistor->clear(Offer::PERSIST_NAME);

                if ($this->getRequest()->getParam('back')) {
                    return $redirect->setPath('dndoffers/*/edit', [
                        'id' => $model->getId()
                    ]);
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                if ($offerId) {
                    $redirect->setPath('dndoffers/*/edit', ['id' => $offerId]);
                } else {
                    $redirect->setPath('dndoffers/*/newAction');
                }

                return $redirect;
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the offer data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->dataPersistor->set(Offer::PERSIST_NAME, $data);

                return $redirect->setPath('dndoffers/*/edit', ['id' => $offerId]);
            }
        }

        return $redirect->setPath('dndoffers/*/');
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareData(array $data): array
    {
        if (isset($data[OfferInterface::ID])) {
            $data[OfferInterface::ID] = (int) $data[OfferInterface::ID] ?: null;
        }
        if (isset($data[OfferInterface::IMAGE][0]['name'])) {
            $data[OfferInterface::IMAGE] = $data[OfferInterface::IMAGE][0]['name'];
        }
        if (isset($data[OfferInterface::STORE_IDS])) {
            $data[OfferInterface::STORE_IDS] = implode(',', $data[OfferInterface::STORE_IDS]);
        }
        if (isset($data[OfferInterface::CATEGORY_IDS])) {
            $data[OfferInterface::CATEGORY_IDS] = implode(',', $data[OfferInterface::CATEGORY_IDS]);
        }

        return $data;
    }
}
