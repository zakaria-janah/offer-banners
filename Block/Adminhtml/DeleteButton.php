<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Block\Adminhtml;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData(): array
    {
        $data = [];
        $offerId = $this->getOfferId();
        if ($offerId) {
            $data = [
                'label'      => __('Delete'),
                'class'      => 'delete',
                'on_click'   => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this?') . '\', \''
                    . $this->getUrlBuilder()->getUrl('*/*/delete', ['id' => $offerId]) . '\')',
                'sort_order' => 20,
            ];
        }

        return $data;
    }
}
