<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model;

use Exception;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Image;
use Magento\Framework\ImageFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ImageProcessor
 */
class ImageProcessor
{
    const MEDIA_PATH     = 'dnd/offers';
    const MEDIA_TMP_PATH = 'dnd/offers/tmp';
    const GIF_EXTENSION  = 'gif';

    /** @var ImageUploader */
    private ImageUploader $imageUploader;

    /** @var ImageFactory */
    private ImageFactory $imageFactory;

    /** @var StoreManagerInterface */
    private StoreManagerInterface $storeManager;

    /** @var WriteInterface|null */
    private ?WriteInterface $mediaDirectory = null;

    /** @var Filesystem */
    private Filesystem $filesystem;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /**
     * ImageProcessor constructor.
     *
     * @param Filesystem $filesystem
     * @param ImageUploader $imageUploader
     * @param ImageFactory $imageFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Filesystem $filesystem,
        ImageUploader $imageUploader,
        ImageFactory $imageFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->imageUploader = $imageUploader;
        $this->imageFactory = $imageFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @param string $iconName
     * @return string
     * @throws LocalizedException
     */
    public function saveImage(string $iconName): string
    {
        try {
            $path = $this->imageUploader->moveFileFromTmp($iconName, true);
            $path = explode('/', $path);
            $iconName = end($path);

            if ($this->isCanResize($iconName)) {
                $this->updateImage($iconName);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        return $iconName;
    }

    /**
     * @param string $imageName
     * @return string
     */
    public function getThumbnailUrl(string $imageName): string
    {
        try {
            return $this->getImageMediaUrl(self::MEDIA_PATH) . '/' . $imageName;
        } catch (NoSuchEntityException) {
            return '';
        }
    }

    /**
     * @param string $mediaPath
     * @return string
     * @throws NoSuchEntityException
     */
    private function getImageMediaUrl(string $mediaPath): string
    {
        return $this->storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $mediaPath;
    }

    /**
     * @param string $image
     * @return bool
     */
    private function isCanResize(string $image): bool
    {
        return $this->getImageExtension($image) !== self::GIF_EXTENSION;
    }

    /**
     * @param string $imageName
     * @return string|null
     */
    private function getImageExtension(string $imageName): ?string
    {
        $imageName = explode('.', $imageName);

        return end($imageName);
    }

    /**
     * @param string $iconName
     * @return void
     */
    private function updateImage(string $iconName): void
    {
        $filename = $this->getMediaDirectory()->getAbsolutePath($this->getImageRelativePath($iconName));

        /** @var Image $imageProcessor */
        $imageProcessor = $this->imageFactory->create(['fileName' => $filename]);
        $imageProcessor->keepAspectRatio(true);
        $imageProcessor->keepFrame(true);
        $imageProcessor->keepTransparency(true);
        $imageProcessor->backgroundColor([255, 255, 255]);
        $imageProcessor->save();
    }

    /**
     * @return WriteInterface
     * @throws FileSystemException
     */
    private function getMediaDirectory(): WriteInterface
    {
        if ($this->mediaDirectory === null) {
            $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        }

        return $this->mediaDirectory;
    }

    /**
     * @param string $iconName
     * @return string
     */
    private function getImageRelativePath(string $iconName): string
    {
        return self::MEDIA_PATH . DIRECTORY_SEPARATOR . $iconName;
    }
}
