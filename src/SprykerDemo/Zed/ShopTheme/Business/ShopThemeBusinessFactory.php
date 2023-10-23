<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business;

use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerDemo\Service\UrlBuilder\UrlBuilderServiceInterface;
use SprykerDemo\Zed\ShopTheme\Business\Activator\ShopThemeActivator;
use SprykerDemo\Zed\ShopTheme\Business\Activator\ShopThemeActivatorInterface;
use SprykerDemo\Zed\ShopTheme\Business\Reader\ActiveThemeReader;
use SprykerDemo\Zed\ShopTheme\Business\Reader\ActiveThemeReaderInterface;
use SprykerDemo\Zed\ShopTheme\Business\Writer\Saver\ShopThemeLogoSaver;
use SprykerDemo\Zed\ShopTheme\Business\Writer\Saver\ShopThemeLogoSaverInterface;
use SprykerDemo\Zed\ShopTheme\Business\Writer\ShopThemeWriter;
use SprykerDemo\Zed\ShopTheme\Business\Writer\ShopThemeWriterInterface;
use SprykerDemo\Zed\ShopTheme\ShopThemeDependencyProvider;

/**
 * @method \SprykerDemo\Zed\ShopTheme\ShopThemeConfig getConfig()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface getRepository()()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface getEntityManager()
 */
class ShopThemeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerDemo\Zed\ShopTheme\Business\Writer\Saver\ShopThemeLogoSaverInterface
     */
    public function createShopThemeLogoSaver(): ShopThemeLogoSaverInterface
    {
        return new ShopThemeLogoSaver(
            $this->getConfig(),
            $this->getUrlBuilderService(),
            $this->getFilesystemService(),
        );
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Business\Writer\ShopThemeWriterInterface
     */
    public function createShopThemeWriter(): ShopThemeWriterInterface
    {
        return new ShopThemeWriter(
            $this->createShopThemeLogoSaver(),
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Business\Reader\ActiveThemeReaderInterface
     */
    public function createActiveThemeReader(): ActiveThemeReaderInterface
    {
        return new ActiveThemeReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Business\Activator\ShopThemeActivatorInterface
     */
    public function createShopThemeActivator(): ShopThemeActivatorInterface
    {
        return new ShopThemeActivator($this->getEntityManager(), $this->getRepository());
    }

    /**
     * @return \SprykerDemo\Service\UrlBuilder\UrlBuilderServiceInterface
     */
    public function getUrlBuilderService(): UrlBuilderServiceInterface
    {
        return $this->getProvidedDependency(ShopThemeDependencyProvider::SERVICE_URL_BUILDER);
    }

    /**
     * @return \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    public function getFilesystemService(): FileSystemServiceInterface
    {
        return $this->getProvidedDependency(ShopThemeDependencyProvider::SERVICE_FILESYSTEM);
    }
}
