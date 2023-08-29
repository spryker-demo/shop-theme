<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence\Mapper;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

class ShopThemeStoreRelationMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShopTheme\Persistence\SpyShopThemeStore> $shopThemeStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapShopThemeStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $shopThemeStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($shopThemeStoreEntities as $shopThemeStoreEntity) {
            $storeTransfer = $this->mapStoreEntityToStoreTransfer($shopThemeStoreEntity->getSpyStore(), new StoreTransfer());
            $storeRelationTransfer->addStores($storeTransfer);
            $storeRelationTransfer->addIdStores($storeTransfer->getIdStoreOrFail());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }
}
