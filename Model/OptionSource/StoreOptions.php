<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model\OptionSource;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\System\Store;

/**
 * Class StoreOptions
 */
class StoreOptions implements OptionSourceInterface
{
    /** @var Store */
    private Store $store;

    /**
     * StoreOptions constructor.
     *
     * @param Store $store
     */
    public function __construct(
        Store $store
    ) {
        $this->store = $store;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return $this->store->getStoreValuesForForm(false, true);
    }
}
