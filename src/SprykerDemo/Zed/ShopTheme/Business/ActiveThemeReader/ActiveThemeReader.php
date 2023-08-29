<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\ActiveThemeReader;

use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;

class ActiveThemeReader implements ActiveThemeReaderInterface
{
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
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function getActiveTheme(?StoreTransfer $storeTransfer = null): ShopThemeTransfer
    {
        return $this->repository->getActiveTheme($storeTransfer ?: $this->storeFacade->getCurrentStore());
    }
}
