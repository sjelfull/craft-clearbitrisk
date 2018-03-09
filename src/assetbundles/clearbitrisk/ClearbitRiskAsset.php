<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk\assetbundles\ClearbitRisk;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 */
class ClearbitRiskAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@superbig/clearbitrisk/assetbundles/clearbitrisk/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/ClearbitRisk.js',
        ];

        $this->css = [
            'css/ClearbitRisk.css',
        ];

        parent::init();
    }
}
