<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model\ResourceModel;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Model\ImageProcessor;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Offer
 */
class Offer extends AbstractDb
{
    /** @var ImageProcessor */
    private ImageProcessor $imageProcessor;

    /**
     * Offer constructor.
     *
     * @param Context $context
     * @param ImageProcessor $imageProcessor
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        ImageProcessor $imageProcessor,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(OfferInterface::MAIN_TABLE, OfferInterface::ID);
    }

    /**
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _beforeSave(AbstractModel $object): AbstractDb
    {
        $this->saveImage($object);

        return parent::_beforeSave($object);
    }

    /**
     * @param AbstractModel $object
     * @return void
     * @throws FileSystemException|LocalizedException
     */
    protected function saveImage(AbstractModel $object): void
    {
        $image = $object->getData(OfferInterface::IMAGE);
        if ($object->dataHasChangedFor(OfferInterface::IMAGE) && $image) {
            $savedImage = $this->imageProcessor->saveImage($image);
            if ($image != $savedImage) {
                $object->setData(OfferInterface::IMAGE, $savedImage);
            }
        }
    }
}
