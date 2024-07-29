<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Controller\Adminhtml\Index;

use Dnd\Offers\Api\Data\OfferInterface;
use Magento\Framework\Phrase;

/**
 * Class MassEnable
 */
class MassEnable extends AbstractMassAction
{
    /**
     * @inheritDoc
     */
    protected function itemAction(OfferInterface $offer): void
    {
        $this->offerRepository->enable($offer);
    }

    /**
     * @inheritDoc
     */
    protected function getErrorMessage(): Phrase
    {
        return __('We can\'t enable item right now. Please review the log and try again.');
    }

    /**
     * @inheritDoc
     */
    protected function getSuccessMessage(int $collectionSize = 0): Phrase
    {
        if ($collectionSize) {
            return __('A total of %1 record(s) have been enabled.', $collectionSize);
        }

        return __('No records have been enabled.');
    }
}
