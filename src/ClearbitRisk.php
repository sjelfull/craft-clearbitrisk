<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk;

use craft\elements\User;
use craft\events\UserEvent;
use craft\services\Elements;
use craft\events\ElementEvent;
use superbig\clearbitrisk\services\ClearbitRiskService as ClearbitRiskServiceService;
use superbig\clearbitrisk\variables\ClearbitRiskVariable;
use superbig\clearbitrisk\models\Settings;
use superbig\clearbitrisk\widgets\ClearbitRiskWidget as ClearbitRiskWidgetWidget;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\console\Application as ConsoleApplication;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class ClearbitRisk
 *
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 *
 * @property  ClearbitRiskServiceService $clearbitRiskService
 * @method  Settings getSettings()
 */
class ClearbitRisk extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var ClearbitRisk
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init ()
    {
        parent::init();
        self::$plugin = $this;

        if ( Craft::$app instanceof ConsoleApplication ) {
            $this->controllerNamespace = 'superbig\clearbitrisk\console\controllers';
        }

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'clearbit-risk/default';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'clearbit-risk/default/do-something';
            }
        );

        Event::on(
            Dashboard::class,
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ClearbitRiskWidgetWidget::class;
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('clearbitRisk', ClearbitRiskVariable::class);
            }
        );

        Event::on(
            Elements::class,
            Elements::EVENT_AFTER_SAVE_ELEMENT,
            function (ElementEvent $event) {
                if ( $event->isNew && $event->element instanceof User ) {
                    $this->clearbitRiskService->onSignup($event->element);
                }
            }
        );

        /*Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ( $event->plugin === $this ) {
                }
            }
        );*/

        Craft::info(
            Craft::t(
                'clearbit-risk',
                '{name} plugin loaded',
                [ 'name' => $this->name ]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel ()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml (): string
    {
        return Craft::$app->view->renderTemplate(
            'clearbit-risk/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
