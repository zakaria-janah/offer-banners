<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Block\Adminhtml;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData(): array
    {
        return [
            'label'    => __('Save'),
            'class'    => 'save primary',
            'on_click' => '',
        ];
    }
}
