<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model\OptionSource;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 */
class Status implements ArrayInterface
{
    const DISABLED = 0;
    const ENABLED = 1;

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::ENABLED,
                'label' => __('Enable')
            ],
            [
                'value' => self::DISABLED,
                'label' => __('Disable')
            ]
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ENABLED  => __('Enable'),
            self::DISABLED => __('Disable')
        ];
    }
}
