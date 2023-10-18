<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\ShopThemeResponseTransfer;
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
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopTheme(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): ?ShopThemeTransfer
    {
        return $this->getRepository()->findShopTheme($shopThemeCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ShopThemeTransfer>
     */
    public function getShopThemes(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): array
    {
        return $this->getRepository()->getShopThemes($shopThemeCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return array<int>
     */
    public function getShopThemeIds(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): array
    {
        return $this->getRepository()->getShopThemeIds($shopThemeCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findActiveTheme(StoreTransfer $storeTransfer): ?ShopThemeTransfer
    {
        return $this->getFactory()
            ->createActiveThemeReader()
            ->findActiveTheme($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeResponseTransfer
     */
    public function saveShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeResponseTransfer
    {
        return $this->getFactory()->createShopThemeWriter()->saveShopTheme($shopThemeTransfer);
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
    public function deleteShopThemeById(int $idShopTheme): void
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
    public function deactivateThemeById(int $idShopTheme): void
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
    public function activateThemeById(int $idShopTheme): ActivateShopThemeActionResponseTransfer
    {
        return $this->getFactory()->createShopThemeActivator()->activate($idShopTheme);
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return bool
     */
    public function shopThemeExists(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): bool
    {
        return $this->getRepository()->shopThemeExists($shopThemeCriteriaTransfer);
    }
}
