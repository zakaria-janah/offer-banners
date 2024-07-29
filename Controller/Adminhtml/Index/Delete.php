<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Controller\Adminhtml\Index;

use Dnd\Offers\Api\OfferRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class Delete
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dnd_Offers::offers';

    /** @var OfferRepositoryInterface */
    private OfferRepositoryInterface $offerRepository;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param OfferRepositoryInterface $offerRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        OfferRepositoryInterface $offerRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->offerRepository = $offerRepository;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute(): ResponseInterface|ResultInterface
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $offerId = (int)$this->getRequest()->getParam('id');
        if ($offerId) {
            try {
                $this->offerRepository->deleteById($offerId);
                $this->messageManager->addSuccessMessage(__('The offer have been deleted.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Can\'t delete offer right now. Please review the log and try again.')
                );
                $this->logger->critical($e);

                return $redirect->setPath('*/*/edit', ['id' => $offerId]);
            }
        }

        return $redirect->setPath('*/*');
    }
}
