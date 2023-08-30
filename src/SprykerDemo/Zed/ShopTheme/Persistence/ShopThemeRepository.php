<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemePersistenceFactory getFactory()
 */
class ShopThemeRepository extends AbstractRepository implements ShopThemeRepositoryInterface
{
    /**
     * @var string
     */
    protected const ACTIVE = 'active';

    /**
     * @var string
     */
    protected const THEME = 'theme';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function getActiveTheme(StoreTransfer $storeTransfer): ShopThemeTransfer
    {
        /** @var \Orm\Zed\ShopTheme\Persistence\SpyShopThemeStoreQuery $shopThemeEntityQuery */
        $shopThemeEntityQuery = $this->getFactory()
            ->createShopThemeQuery()
            ->joinSpyShopThemeStore()
            ->useSpyShopThemeStoreQuery()
                ->joinSpyStore()
                ->useSpyStoreQuery()
                    ->filterByName($storeTransfer->getName())
                ->endUse();

        /** @var \Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery $shopThemeEntityQuery */
        $shopThemeEntityQuery = $shopThemeEntityQuery->endUse();
        $shopThemeEntityQuery = $shopThemeEntityQuery->filterByStatus(static::ACTIVE);

        $shopThemeEntity = $shopThemeEntityQuery->findOne();

        if (!$shopThemeEntity) {
            return new ShopThemeTransfer();
        }

        return $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                $shopThemeEntity,
                new ShopThemeTransfer(),
            );
    }

    /**
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopThemeById(int $idShopTheme): ?ShopThemeTransfer
    {
        /** @var \Orm\Zed\ShopTheme\Persistence\SpyShopThemeStoreQuery $shopThemeEntityQuery */
        $shopThemeEntityQuery = $this->getFactory()
            ->createShopThemeQuery()
            ->joinSpyShopThemeStore()
            ->useSpyShopThemeStoreQuery()
                ->joinSpyStore()
                ->useSpyStoreQuery()
                ->endUse();

        /** @var \Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery $shopThemeEntityQuery */
        $shopThemeEntityQuery = $shopThemeEntityQuery->endUse();
        $shopThemeEntityQuery = $shopThemeEntityQuery->filterByIdShopTheme($idShopTheme);

        $shopThemeEntity = $shopThemeEntityQuery->findOne();

        if (!$shopThemeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                $shopThemeEntity,
                new ShopThemeTransfer(),
            );
    }
}
