<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Activator;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;

class ShopThemeActivator implements ShopThemeActivatorInterface
{
    /**
     * @uses \Orm\Zed\ShopTheme\Persistence\Map\SpyShopThemeTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const STATUS_ACTIVE = 'active';

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface
     */
    protected ShopThemeEntityManagerInterface $entityManager;

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface
     */
    protected ShopThemeRepositoryInterface $repository;

    /**
     * @var string
     */
    protected const CONFLICTING_ERROR_MESSAGE = 'Your theme cannot be activated, because there is already theme "%s" active with the store relation %s';

    /**
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface $entityManager
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface $repository
     */
    public function __construct(ShopThemeEntityManagerInterface $entityManager, ShopThemeRepositoryInterface $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
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
        $storeIds = $this->repository->getShopThemeStoreIds($idShopTheme);
        $conflictingShopTheme = $this->findConflictingShopTheme($idShopTheme, $storeIds);

        if (!$conflictingShopTheme || !$conflictingShopTheme->getStoreRelation()) {
            $this->entityManager->activate($idShopTheme);

            return $responseTransfer;
        }

        $responseTransfer->setIsSuccess(false);
        $responseTransfer->setErrorMessage($this->createConflictingShopThemeErrorMessage($conflictingShopTheme));

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $conflictingShopTheme
     *
     * @return string
     */
    protected function createConflictingShopThemeErrorMessage(ShopThemeTransfer $conflictingShopTheme): string
    {
        return sprintf(
            static::CONFLICTING_ERROR_MESSAGE,
            $conflictingShopTheme->getName(),
            implode(', ', array_reduce(
                $conflictingShopTheme->getStoreRelation()->getStores()->getArrayCopy(),
                static fn (array $carry, StoreTransfer $storeTransfer) => array_merge($carry, [$storeTransfer->getName()]),
                [],
            )),
        );
    }

    /**
     * @param int $shopThemeId
     * @param array<int> $storeIds
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    protected function findConflictingShopTheme(int $shopThemeId, array $storeIds): ?ShopThemeTransfer
    {
        return $this->repository->findShopTheme(
            (new ShopThemeCriteriaTransfer())
                ->setExcludedShopThemeIds([$shopThemeId])
                ->setWithStoreRelations(true)
                ->setStoreIds($storeIds)
                ->setStatus(static::STATUS_ACTIVE),
        );
    }
}
