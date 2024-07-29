<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Dnd\Offers\Api\OfferRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Dnd\Offers\Api\Data\OfferInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Dnd\Offers\Model\ImageProcessor;

/**
 * Class OffersProviderViewModel
 */
class OffersProviderViewModel implements ArgumentInterface
{
    protected SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    protected OfferRepositoryInterface $offerRepository;

    protected Resolver $layerResolver;

    protected StoreManagerInterface $storeManager;

    protected ImageProcessor $imageProcessor;
    
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        OfferRepositoryInterface $offerRepository,
        Resolver $layerResolver,
        StoreManagerInterface $storeManager,
        ImageProcessor $imageProcessor
    ) {
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->offerRepository = $offerRepository;
        $this->layerResolver = $layerResolver;
        $this->storeManager = $storeManager;
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * @return OfferInterface[]
     */
    public function getOffers(): array
    {
        /** @var SearchCriteriaBuilder $searchCriteriaBuilder * */
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        return $this->offerRepository->getList(
                $searchCriteriaBuilder->create(),
                (int)$this->getCurrentCategory()->getId(),
                (int)$this->storeManager->getStore()->getId()
            )->getItems();
    }

    /**
     * @param string $image
     * @return string
     */
    public function getImageUrl(string $image): string
    {
        return $this->imageProcessor->getThumbnailUrl($image);
    }

    /**
     * @return CategoryInterface
     */
    private function getCurrentCategory(): CategoryInterface
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }
}
