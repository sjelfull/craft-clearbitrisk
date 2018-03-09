<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk\widgets;

use superbig\clearbitrisk\ClearbitRisk;
use superbig\clearbitrisk\assetbundles\clearbitriskwidgetwidget\ClearbitRiskWidgetWidgetAsset;

use Craft;
use craft\base\Widget;

/**
 * Clearbit Risk Widget
 *
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 */
class ClearbitRiskWidget extends Widget
{

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $message = 'Hello, world.';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('clearbit-risk', 'ClearbitRiskWidget');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias("@superbig/clearbitrisk/assetbundles/clearbitriskwidgetwidget/dist/img/ClearbitRiskWidget-icon.svg");
    }

    /**
     * @inheritdoc
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['message', 'string'],
                ['message', 'default', 'value' => 'Hello, world.'],
            ]
        );
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'clearbit-risk/_components/widgets/ClearbitRiskWidget_settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        Craft::$app->getView()->registerAssetBundle(ClearbitRiskWidgetWidgetAsset::class);

        return Craft::$app->getView()->renderTemplate(
            'clearbit-risk/_components/widgets/ClearbitRiskWidget_body',
            [
                'message' => $this->message
            ]
        );
    }
}
