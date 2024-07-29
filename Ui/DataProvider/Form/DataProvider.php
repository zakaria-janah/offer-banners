<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Ui\DataProvider\Form;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Model\ImageProcessor;
use Dnd\Offers\Model\Offer;
use Dnd\Offers\Model\ResourceModel\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /** @var Registry */
    private Registry $coreRegistry;

    /** @var ImageProcessor */
    private ImageProcessor $imageProcessor;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param Registry $coreRegistry
     * @param ImageProcessor $imageProcessor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        Registry $coreRegistry,
        ImageProcessor $imageProcessor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->coreRegistry = $coreRegistry;
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $result = parent::getData();
        /** @var Offer $current */
        $current = $this->coreRegistry->registry(OfferInterface::PERSIST_NAME);
        if ($current->getId()) {
            $data = $this->prepareData($current->getData());
            $result[$current->getId()] = $data;
        }

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data): array
    {
        if (isset($data[OfferInterface::IMAGE])) {
            $data[OfferInterface::IMAGE] = [
                [
                    'name' => $data[OfferInterface::IMAGE],
                    'url'  => $this->imageProcessor->getThumbnailUrl($data[OfferInterface::IMAGE])
                ]
            ];
        }

        return $data;
    }
}
