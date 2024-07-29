<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Controller\Adminhtml\Index;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Api\OfferRepositoryInterface;
use Dnd\Offers\Model\OfferFactory;
use Dnd\Offers\Model\ResourceModel\CollectionFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractMassAction
 */
abstract class AbstractMassAction extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dnd_Offers::offers';

    /** @var LoggerInterface */
    protected LoggerInterface $logger;

    /** @var OfferRepositoryInterface */
    protected OfferRepositoryInterface $offerRepository;

    /** @var CollectionFactory */
    protected CollectionFactory $collectionFactory;

    /** @var OfferFactory */
    protected OfferFactory $offerFactory;

    /** @var Filter */
    private Filter $filter;

    /**
     * AbstractMassAction constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param LoggerInterface $logger
     * @param OfferRepositoryInterface $offerRepository
     * @param CollectionFactory $collectionFactory
     * @param OfferFactory $offerFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        LoggerInterface $logger,
        OfferRepositoryInterface $offerRepository,
        CollectionFactory $collectionFactory,
        OfferFactory $offerFactory
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
        $this->collectionFactory = $collectionFactory;
        $this->offerFactory = $offerFactory;
        $this->filter = $filter;
    }

    /**
     * Execute action for group
     *
     * @param OfferInterface $offer
     * @return void
     */
    abstract protected function itemAction(OfferInterface $offer): void;

    /**
     * Mass action execution
     *
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute(): void
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        if ($size = $collection->getSize()) {
            try {
                foreach ($collection->getItems() as $model) {
                    $this->itemAction($model);
                }
                $this->messageManager->addSuccessMessage($this->getSuccessMessage($size));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($this->getErrorMessage());
                $this->logger->critical($e);
            }
        }
        $this->_redirect($this->_redirect->getRefererUrl());
    }

    /**
     * @return Phrase
     */
    protected function getErrorMessage(): Phrase
    {
        return __('We can\'t change item right now. Please review the log and try again.');
    }

    /**
     * @param int $collectionSize
     * @return Phrase
     */
    protected function getSuccessMessage(int $collectionSize = 0): Phrase
    {
        if ($collectionSize) {
            return __('A total of %1 record(s) have been changed.', $collectionSize);
        }

        return __('No records have been changed.');
    }
}
