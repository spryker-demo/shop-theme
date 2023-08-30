<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ShopTheme\Persistence\Map\SpyShopThemeTableMap;
use Orm\Zed\ShopTheme\Persistence\SpyShopThemeStore;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemePersistenceFactory getFactory()
 */
class ShopThemeEntityManager extends AbstractEntityManager implements ShopThemeEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const STATUS = 'Status';

    /**
     * @var string
     */
    protected const ACTIVE = SpyShopThemeTableMap::COL_STATUS_ACTIVE;

    /**
     * @var string
     */
    protected const INACTIVE = SpyShopThemeTableMap::COL_STATUS_INACTIVE;

    /**
     * @var string
     */
    protected const CONFLICTING_ERROR_MESSAGE = 'Your theme cannot be activated, because there is already theme "%s" active with the store relation %s';

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function saveShopTheme(
        ShopThemeTransfer $shopThemeTransfer
    ): ShopThemeTransfer {
        $shopThemeEntity = $this->getFactory()
            ->createShopThemeQuery()
            ->filterByIdShopTheme($shopThemeTransfer->getIdShopTheme())
            ->findOneOrCreate();

        $shopThemeEntity = $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeTransferToShopThemeEntity(
                $shopThemeTransfer,
                $shopThemeEntity,
            );

        $shopThemeEntity->save();

        $currentShopThemeTransfer = $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                $shopThemeEntity,
                new ShopThemeTransfer(),
            );

        $this->saveShopThemeStoreRelations($currentShopThemeTransfer, $shopThemeTransfer);

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
            ->delete();
    }

    /**
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer
     */
    public function activate(int $idShopTheme): ActivateShopThemeActionResponseTransfer
    {
        $responseTransfer = new ActivateShopThemeActionResponseTransfer();
        $responseTransfer->setIsSuccess(true);

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
            $responseTransfer->setIsSuccess(false);
            $responseTransfer->setErrorMessage('Shop theme doesn\'t exist');

            return $responseTransfer;
        }

        $shopThemeTransfer = $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                $shopThemeEntity,
                new ShopThemeTransfer(),
            );

        $conflictingShopThemeTransfer = $this->findConflictingShopTheme($shopThemeTransfer);

        if ($conflictingShopThemeTransfer) {
            $responseTransfer->setIsSuccess(false);
            $responseTransfer->setErrorMessage($this->createConflictingShopThemeErrorMessage($conflictingShopThemeTransfer));

            return $responseTransfer;
        }

        $shopThemeEntity->setStatus(static::ACTIVE)->save();

        return $responseTransfer;
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

        $shopThemeEntity->setStatus(static::INACTIVE)->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $currentShopThemeTransfer
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $newShopThemeTransfer
     *
     * @return void
     */
    protected function saveShopThemeStoreRelations(ShopThemeTransfer $currentShopThemeTransfer, ShopThemeTransfer $newShopThemeTransfer): void
    {
        $idShopTheme = $currentShopThemeTransfer->getIdShopThemeOrFail();
        $currentStoreAssignment = $currentShopThemeTransfer->getStoreRelation() ?? new StoreRelationTransfer();
        $newStoreAssignment = $newShopThemeTransfer->getStoreRelation() ?? new StoreRelationTransfer();

        $storeIdsToAdd = $this->getStoreIdsToAdd(
            $currentStoreAssignment,
            $newStoreAssignment,
        );
        $storeIdsToDelete = $this->getStoreIdsToDelete(
            $currentStoreAssignment,
            $newStoreAssignment,
        );

        if ($storeIdsToAdd === [] && $storeIdsToDelete === []) {
            return;
        }

        $this->deleteStoreRelations($idShopTheme, $storeIdsToDelete);
        $this->addStoreRelations($idShopTheme, $storeIdsToAdd);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     *
     * @return array<int>
     */
    protected function getStoreIdsToAdd(
        StoreRelationTransfer $existingStoreRelationTransfer,
        StoreRelationTransfer $newStoreRelationTransfer
    ): array {
        $storeIdsToAdd = array_diff($newStoreRelationTransfer->getIdStores(), $existingStoreRelationTransfer->getIdStores());

        return $storeIdsToAdd;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     *
     * @return array<int>
     */
    protected function getStoreIdsToDelete(
        StoreRelationTransfer $existingStoreRelationTransfer,
        StoreRelationTransfer $newStoreRelationTransfer
    ): array {
        $storeIdsToDelete = array_diff($existingStoreRelationTransfer->getIdStores(), $newStoreRelationTransfer->getIdStores());

        return $storeIdsToDelete;
    }

    /**
     * @param int $idShopTheme
     * @param array<int> $storeIdsToAdd
     *
     * @return void
     */
    protected function addStoreRelations(int $idShopTheme, array $storeIdsToAdd): void
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
    protected function deleteStoreRelations(int $idShopTheme, array $storeIdsToDelete): void
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

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeEntity
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    protected function findConflictingShopTheme(ShopThemeTransfer $shopThemeEntity): ?ShopThemeTransfer
    {
        /** @var \Orm\Zed\ShopTheme\Persistence\SpyShopThemeStoreQuery $shopThemeEntitiesQuery */
        $shopThemeEntitiesQuery = $this->getFactory()
            ->createShopThemeQuery()
            ->leftJoinWithSpyShopThemeStore()
            ->useSpyShopThemeStoreQuery()
                ->leftJoinWithSpyStore()
                ->useSpyStoreQuery()
                    ->filterByIdStore_In($shopThemeEntity->getStoreRelation()->getIdStores())
                ->endUse();

        /** @var \Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery $shopThemeEntitiesQuery */
        $shopThemeEntitiesQuery = $shopThemeEntitiesQuery->endUse();
        $shopThemeEntitiesQuery->filterByStatus(static::ACTIVE)
            ->filterByIdShopTheme($shopThemeEntity->getIdShopTheme(), Criteria::NOT_IN);
        $shopThemeEntities = $shopThemeEntitiesQuery->find();

        if (!$shopThemeEntities->count()) {
            return null;
        }

        $shopThemeEntity = $shopThemeEntities->offsetGet(0);

        $shopThemeTransfer = $this->getFactory()
            ->createShopThemeMapper()
            ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                $shopThemeEntity,
                new ShopThemeTransfer(),
            );

        return $shopThemeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $conflictingShopThemeTransfer
     *
     * @return string
     */
    protected function createConflictingShopThemeErrorMessage(ShopThemeTransfer $conflictingShopThemeTransfer): string
    {
        return sprintf(
            static::CONFLICTING_ERROR_MESSAGE,
            $conflictingShopThemeTransfer->getName(),
            implode(', ', array_reduce(
                $conflictingShopThemeTransfer->getStoreRelation()->getStores()->getArrayCopy(),
                static fn (array $carry, StoreTransfer $storeTransfer) => array_merge($carry, [$storeTransfer->getName()]),
                [],
            )),
        );
    }
}
