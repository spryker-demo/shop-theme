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
        return !$this->repository->shopThemeExists(
            (new ShopThemeCriteriaTransfer())->setStatus(static::ACTIVE)
                ->setExcludedShopThemeIds([$shopThemeId])
                ->setStoreIds($storeRelation->getIdStores()),
        );
    }
}
