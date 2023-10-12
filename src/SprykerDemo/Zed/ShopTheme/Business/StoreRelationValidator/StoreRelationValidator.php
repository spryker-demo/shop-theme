<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\StoreRelationValidator;

use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;

class StoreRelationValidator implements StoreRelationValidatorInterface
{
    /**
     * @var string
     */
    public const ACTIVE = 'active';

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface
     */
    protected ShopThemeRepositoryInterface $repository;

    /**
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface $repository
     */
    public function __construct(ShopThemeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $shopThemeId
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return bool
     */
    public function validateStoreRelationForShopThemeByShopThemeId(int $shopThemeId, StoreRelationTransfer $storeRelation): bool
    {
        $shopThemeTransfer = $this->repository->findShopTheme(
            (new ShopThemeCriteriaTransfer())->setStatus(static::ACTIVE)
                ->setWithStoreRelations(true)
                ->setIdShopTheme($shopThemeId),
        );

        if (!$shopThemeTransfer) {
            return true;
        }

        $storeIds = $this->extractStoreIds($shopThemeTransfer->getStoreRelation());
        $shopThemeTransfer = $this->repository->findShopTheme(
            (new ShopThemeCriteriaTransfer())->setStatus(static::ACTIVE)
                ->setExcludedShopThemeIds([$shopThemeId])
                ->setStoreIds($storeIds),
        );

        if (!$shopThemeTransfer) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return array<int>
     */
    protected function extractStoreIds(StoreRelationTransfer $storeRelationTransfer): array
    {
        $storeIds = [];

        foreach ($storeRelationTransfer->getStores() as $store) {
            $storeIds[] = $store->getIdStore();
        }

        return $storeIds;
    }
}
