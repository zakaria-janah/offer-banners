<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Ui\Component\Listing\Columns;

use Magento\Framework\Phrase;
use Magento\Store\Ui\Component\Listing\Column\Store;

/**
 * Class Stores
 */
class Stores extends Store
{
    /**
     * Get data
     *
     * @param array $item
     * @return string
     */
    protected function prepareItem(array $item): Phrase|string
    {
        if (isset($item[$this->storeKey])) {
            if (!$item[$this->storeKey]) {
                $item[$this->storeKey] = [0];
            }

            if (is_string($item[$this->storeKey])) {
                $item[$this->storeKey] = explode(',', $item[$this->storeKey]);
            }
        }

        return parent::prepareItem($item);
    }
}
