<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme;

use Spryker\Shared\Config\Config;
use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ShopThemeConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const LOGO_FILESYSTEM_NAME = 'logo';

    /**
     * @uses \Orm\Zed\ShopTheme\Persistence\Map\SpyShopThemeTableMap::COL_STATUS_ACTIVE
     */
    public const STATUS_ACTIVE = 'active';

    /**
     * @api
     *
     * @return string
     */
    public function getLogoFilesystemName(): string
    {
        return static::LOGO_FILESYSTEM_NAME;
    }

    /**
     * @api
     *
     * @param string $fileSystemName
     *
     * @return mixed
     */
    public function getFileSystemConfigByName(string $fileSystemName)
    {
        return Config::get(FileSystemConstants::FILESYSTEM_SERVICE)[$fileSystemName];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getFileSystemWriterConfig(): array
    {
        return [];
    }
}
