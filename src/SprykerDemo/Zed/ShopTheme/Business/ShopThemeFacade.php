<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerDemo\Zed\ShopTheme\Business\ShopThemeBusinessFactory getFactory()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface getRepository()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface getEntityManager()
 */
class ShopThemeFacade extends AbstractFacade implements ShopThemeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopThemeById(int $idShopTheme): ?ShopThemeTransfer
    {
        return $this->getRepository()->findShopThemeById($idShopTheme);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function getActiveTheme(?StoreTransfer $storeTransfer = null): ShopThemeTransfer
    {
        return $this->getFactory()
            ->createActiveThemeReader()
            ->getActiveTheme($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function saveShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeTransfer
    {
        return $this->getEntityManager()->saveShopTheme($shopThemeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deleteShopTheme(int $idShopTheme): void
    {
        $this->getEntityManager()->deleteShopTheme($idShopTheme);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deactivateTheme(int $idShopTheme): void
    {
        $this->getEntityManager()->deactivate($idShopTheme);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer
     */
    public function activateTheme(int $idShopTheme): ActivateShopThemeActionResponseTransfer
    {
        return $this->getEntityManager()->activate($idShopTheme);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $shopThemeId
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return bool
     */
    public function validateStoreRelationForShopThemeByShopThemeId(int $shopThemeId, StoreRelationTransfer $storeRelation): bool
    {
        return $this->getFactory()->createStoreRelationValidator()
            ->validateStoreRelationForShopThemeByShopThemeId($shopThemeId, $storeRelation);
    }
}
