<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

/**
 * Class Index
 */
class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dnd_Offers::offers';

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Offers'));
        $this->_view->renderLayout();
    }

    /**
     * Initiate action
     *
     * @return self
     */
    private function initAction(): self
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(self::ADMIN_RESOURCE)
            ->_addBreadcrumb(__('Offers'), __('Offers'));

        return $this;
    }
}
