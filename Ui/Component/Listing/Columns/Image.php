<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Ui\Component\Listing\Columns;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Model\ImageProcessor;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Image
 */
class Image extends Column
{
    /** @var ImageProcessor */
    private ImageProcessor $imageProcessor;

    /**
     * Image constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ImageProcessor $imageProcessor
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ImageProcessor $imageProcessor,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item = $this->prepareData($item);
            }
        }

        return $dataSource;
    }

    /**
     * @param array $item
     * @return array
     */
    public function prepareData(array $item): array
    {
        $fieldName = $this->getData('name');
        if (isset($item[OfferInterface::IMAGE])) {
            $item[$fieldName . '_src'] = $this->imageProcessor->getThumbnailUrl($item[OfferInterface::IMAGE]);
            $item[$fieldName . '_orig_src'] = $item[OfferInterface::IMAGE];
        }

        return $item;
    }
}
