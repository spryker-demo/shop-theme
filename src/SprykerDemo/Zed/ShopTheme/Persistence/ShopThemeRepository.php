<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemePersistenceFactory getFactory()
 */
class ShopThemeRepository extends AbstractRepository implements ShopThemeRepositoryInterface
{
    /**
     * @var string
     */
    protected const COLUMN_NAME_FK_STORE = 'fkStore';

    /**
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopTheme(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): ?ShopThemeTransfer
    {
        $shopThemeQuery = $this->applyFilters($shopThemeCriteriaTransfer, $this->getFactory()->createShopThemeQuery());

        if ($shopThemeCriteriaTransfer->getWithStoreRelations()) {
            $shopThemeQuery->joinSpyShopThemeStore()
                ->useSpyShopThemeStoreQuery()
                    ->joinSpyStore()
                ->endUse();
        }

        $shopThemeEntity = $shopThemeQuery->findOne();

        if (!$shopThemeEntity) {
            return null;
        }

        if ($shopThemeCriteriaTransfer->getWithStoreRelations()) {
            return $this->getFactory()
                ->createShopThemeMapper()
                ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                    $shopThemeEntity,
                    new ShopThemeTransfer(),
                );
        }

        return $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeEntityToShopThemeTransfer(
                $shopThemeEntity,
                new ShopThemeTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ShopThemeTransfer>
     */
    public function getShopThemes(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): array
    {
        $shopThemeQuery = $this->applyFilters($shopThemeCriteriaTransfer, $this->getFactory()->createShopThemeQuery());

        if ($shopThemeCriteriaTransfer->getWithStoreRelations()) {
            $shopThemeQuery
                ->joinWithSpyShopThemeStore()
                ->useSpyShopThemeStoreQuery()
                    ->joinWithSpyStore()
                ->endUse();
        }

        $shopThemeEntities = $shopThemeQuery->find()->getData();

        if ($shopThemeCriteriaTransfer->getWithStoreRelations()) {
            return $this->getFactory()->createShopThemeMapper()->mapShopThemeEntitiesToShopThemeTransfersWithStoreRelation($shopThemeEntities);
        }

        return $this->getFactory()->createShopThemeMapper()->mapShopThemeEntitiesToShopThemeTransfers($shopThemeEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return array<int>
     */
    public function getShopThemeIds(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): array
    {
        $shopThemeQuery = $this->applyFilters($shopThemeCriteriaTransfer, $this->getFactory()->createShopThemeQuery());

        if ($shopThemeCriteriaTransfer->getWithStoreRelations()) {
            $shopThemeQuery
                ->joinWithSpyShopThemeStore()
                ->useSpyShopThemeStoreQuery()
                    ->joinWithSpyStore()
                ->endUse();
        }

        return $shopThemeQuery->find()->getColumnValues();
    }

    /**
     * @param int $shopThemeId
     *
     * @return array<int>
     */
    public function getShopThemeStoreIds(int $shopThemeId): array
    {
        $shopThemeStoreQuery = $this->getFactory()
            ->createShopThemeStoreQuery()
            ->filterByFkShopTheme($shopThemeId);

        return $shopThemeStoreQuery->find()->getColumnValues(static::COLUMN_NAME_FK_STORE);
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     * @param \Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery $shopThemeQuery
     *
     * @return \Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery
     */
    protected function applyFilters(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer, SpyShopThemeQuery $shopThemeQuery): SpyShopThemeQuery
    {
        if ($shopThemeCriteriaTransfer->getStatus()) {
            $shopThemeQuery->filterByStatus($shopThemeCriteriaTransfer->getStatus());
        }

        if ($shopThemeCriteriaTransfer->getStoreName()) {
            $shopThemeQuery
                ->useSpyShopThemeStoreQuery()
                    ->useSpyStoreQuery()
                        ->filterByName($shopThemeCriteriaTransfer->getStoreName())
                    ->endUse()
                ->endUse();
        }

        if ($shopThemeCriteriaTransfer->getStoreIds()) {
            $shopThemeQuery
                ->useSpyShopThemeStoreQuery()
                    ->filterByIdShopThemeStore_In($shopThemeCriteriaTransfer->getStoreIds())
                ->endUse();
        }

        if ($shopThemeCriteriaTransfer->getShopThemeIds()) {
            $shopThemeQuery->filterByIdShopTheme_In($shopThemeCriteriaTransfer->getShopThemeIds());
        }

        if ($shopThemeCriteriaTransfer->getIdShopTheme()) {
            $shopThemeQuery->filterByIdShopTheme($shopThemeCriteriaTransfer->getIdShopTheme());
        }

        if ($shopThemeCriteriaTransfer->getExcludedShopThemeIds()) {
            $shopThemeQuery->filterByIdShopTheme($shopThemeCriteriaTransfer->getExcludedShopThemeIds(), Criteria::NOT_IN);
        }

        return $shopThemeQuery;
    }
}
