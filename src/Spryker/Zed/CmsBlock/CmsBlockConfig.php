<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock;

use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const CMS_TWIG_TEMPLATE_PREFIX = '@CmsBlock';

    /**
     * @var string
     */
    protected const CMS_BLOCK_PLACEHOLDER_PATTERN = '/<!-- CMS_BLOCK_PLACEHOLDER : "[a-zA-Z0-9._-]*" -->/';

    /**
     * @var string
     */
    protected const CMS_BLOCK_PLACEHOLDER_VALUE_PATTERN = '/"([^"]+)"/';

    /**
     * @var string
     */
    protected const THEME_NAME_DEFAULT = 'default';

    /**
     * @api
     *
     * @return string
     */
    public function getPlaceholderPattern(): string
    {
        return static::CMS_BLOCK_PLACEHOLDER_PATTERN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPlaceholderValuePattern(): string
    {
        return static::CMS_BLOCK_PLACEHOLDER_VALUE_PATTERN;
    }

    /**
     * @api
     *
     * @param string $templateRelativePath
     *
     * @return array<string>
     */
    public function getTemplateRealPaths($templateRelativePath): array
    {
        $templatePaths = [];

        foreach ($this->getThemeNames() as $themeName) {
            $templatePaths[] = $this->getAbsolutePath($templateRelativePath, 'Shared', $themeName);
        }

        return $templatePaths;
    }

    /**
     * @param string $templateRelativePath
     * @param string $twigLayer
     * @param string $themeName
     *
     * @return string
     */
    protected function getAbsolutePath(
        string $templateRelativePath,
        string $twigLayer,
        string $themeName = self::THEME_NAME_DEFAULT
    ): string {
        $templateRelativePath = str_replace(static::CMS_TWIG_TEMPLATE_PREFIX, '', $templateRelativePath);

        return sprintf(
            '%s/%s/%s/CmsBlock/Theme/%s%s',
            APPLICATION_SOURCE_DIR,
            $this->get(CmsBlockConstants::PROJECT_NAMESPACE),
            $twigLayer,
            $themeName,
            $templateRelativePath,
        );
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getThemeNames(): array
    {
        if ($this->getThemeName() === '' || $this->getThemeName() === $this->getThemeNameDefault()) {
            return [
                $this->getThemeNameDefault(),
            ];
        }

        return [
            $this->getThemeName(),
            $this->getThemeNameDefault(),
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getCmsBlockTemplatePaths(): array
    {
        return [
            sprintf(
                '%s/%s/Shared/CmsBlock/Theme/%s',
                APPLICATION_SOURCE_DIR,
                $this->get(CmsBlockConstants::PROJECT_NAMESPACE),
                static::THEME_NAME_DEFAULT,
            ),
        ];
    }

    /**
     * @return string
     */
    protected function getThemeName(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getThemeNameDefault(): string
    {
        return static::THEME_NAME_DEFAULT;
    }
}
