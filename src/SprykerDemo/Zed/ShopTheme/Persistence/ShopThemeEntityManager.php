<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Generated\Shared\Transfer\ShopThemeTransfer;
use Orm\Zed\ShopTheme\Persistence\Map\SpyShopThemeTableMap;
use Orm\Zed\ShopTheme\Persistence\SpyShopTheme;
use Orm\Zed\ShopTheme\Persistence\SpyShopThemeStore;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemePersistenceFactory getFactory()
 */
class ShopThemeEntityManager extends AbstractEntityManager implements ShopThemeEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function saveShopTheme(
        ShopThemeTransfer $shopThemeTransfer
    ): ShopThemeTransfer {
        $shopThemeEntity = $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeTransferToShopThemeEntity(
                $shopThemeTransfer,
                new SpyShopTheme(),
            );

        $shopThemeEntity->save();
        $shopThemeTransfer->setIdShopTheme($shopThemeEntity->getIdShopTheme());

        return $shopThemeTransfer;
    }

    /**
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deleteShopTheme(int $idShopTheme): void
    {
        $this->getFactory()
            ->createShopThemeQuery()
            ->filterByIdShopTheme($idShopTheme)
            ->find()
            ->delete();
    }

    /**
     * @param int $idShopTheme
     *
     * @return void
     */
    public function activate(int $idShopTheme): void
    {
        $shopThemeEntity = $this->getFactory()
            ->createShopThemeQuery()
            ->filterByIdShopTheme($idShopTheme)
            ->findOne();

        $shopThemeEntity->setStatus(SpyShopThemeTableMap::COL_STATUS_ACTIVE)->save();
    }

    /**
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deactivate(int $idShopTheme): void
    {
        $shopThemeEntity = $this->getFactory()
            ->createShopThemeQuery()
            ->filterByIdShopTheme($idShopTheme)
            ->findOne();

        if (!$shopThemeEntity) {
            return;
        }

        $shopThemeEntity->setStatus(SpyShopThemeTableMap::COL_STATUS_INACTIVE)->save();
    }

    /**
     * @param int $idShopTheme
     * @param array<int> $storeIdsToAdd
     *
     * @return void
     */
    public function saveStoreRelations(int $idShopTheme, array $storeIdsToAdd): void
    {
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyShopThemeStore::class);

        foreach ($storeIdsToAdd as $idStore) {
            $categoryStoreEntity = (new SpyShopThemeStore())
                ->setFkShopTheme($idShopTheme)
                ->setFkStore($idStore);

            $propelCollection->append($categoryStoreEntity);
        }

        $propelCollection->save();
    }

    /**
     * @param int $idShopTheme
     * @param array<int> $storeIdsToDelete
     *
     * @return void
     */
    public function deleteStoreRelations(int $idShopTheme, array $storeIdsToDelete): void
    {
        if ($storeIdsToDelete === []) {
            return;
        }

        $this->getFactory()
            ->createShopThemeStoreQuery()
            ->filterByFkShopTheme($idShopTheme)
            ->filterByFkStore_In($storeIdsToDelete)
            ->find()
            ->delete();
    }
}
