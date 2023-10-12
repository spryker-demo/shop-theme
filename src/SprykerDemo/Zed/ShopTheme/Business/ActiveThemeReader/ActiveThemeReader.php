<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\ActiveThemeReader;

use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;

class ActiveThemeReader implements ActiveThemeReaderInterface
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findActiveTheme(StoreTransfer $storeTransfer): ?ShopThemeTransfer
    {
        return $this->repository->findShopTheme(
            (new ShopThemeCriteriaTransfer())
                ->setStoreName($storeTransfer->getName())
                ->setStatus(static::ACTIVE),
        );
    }
}
