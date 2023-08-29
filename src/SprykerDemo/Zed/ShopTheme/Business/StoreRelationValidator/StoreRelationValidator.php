<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\StoreRelationValidator;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;

class StoreRelationValidator implements StoreRelationValidatorInterface
{
    /**
     * @var string
     */
    public const ACTIVE = 'active';

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected StoreFacadeInterface $storeFacade;

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface
     */
    protected ShopThemeRepositoryInterface $repository;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface $repository
     */
    public function __construct(
        StoreFacadeInterface $storeFacade,
        ShopThemeRepositoryInterface $repository
    ) {
        $this->storeFacade = $storeFacade;
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
        $shopThemeTransfer = $this->repository->findShopThemeById($shopThemeId);
        if (!$shopThemeTransfer) {
            return true;
        }
        if ($shopThemeTransfer->getStatus() !== static::ACTIVE) {
            return true;
        }

        foreach ($storeRelation->getIdStores() as $idStore) {
            $storeTransfer = $this->storeFacade->getStoreById($idStore);
            $activeShopThemeTransfer = $this->repository->getActiveTheme($storeTransfer);
            if ($activeShopThemeTransfer->getIdShopTheme() && $activeShopThemeTransfer->getIdShopTheme() !== $shopThemeId) {
                return false;
            }
        }

        return true;
    }
}
