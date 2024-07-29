<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Ui\Component\Listing\Columns;

use Dnd\Offers\Api\Data\OfferInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 */
class Actions extends Column
{
    /** @var UrlInterface */
    private UrlInterface $urlBuilder;

    /** @var Escaper */
    private Escaper $escaper;

    /**
     * Actions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->escaper = $escaper;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData(OfferInterface::NAME);
                $item[$name]['edit'] = [
                    'href'  => $this->urlBuilder->getUrl(
                        'dndoffers/index/edit',
                        [OfferInterface::ID => $item[OfferInterface::ID]]
                    ),
                    'label' => __('Edit')
                ];
                $title = $this->escaper->escapeHtml($item[OfferInterface::NAME]);
                $item[$name]['delete'] = [
                    'href'    => $this->urlBuilder->getUrl(
                        'dndoffers/index/delete',
                        [OfferInterface::ID => $item[OfferInterface::ID]]
                    ),
                    'label'   => __('Delete'),
                    'confirm' => [
                        'title'   => __('Delete %1', $title),
                        'message' => __('Are you sure you wan\'t to delete a %1 offer?', $title)
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
