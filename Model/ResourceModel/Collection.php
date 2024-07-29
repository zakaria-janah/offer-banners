<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model\ResourceModel;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Model\Offer;
use Dnd\Offers\Model\ResourceModel\Offer as ResourceOffer;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_setIdFieldName(OfferInterface::ID);
        $this->_init(Offer::class, ResourceOffer::class);
    }
}
