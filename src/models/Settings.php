<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk\models;

use superbig\clearbitrisk\ClearbitRisk;

use Craft;
use craft\base\Model;

/**
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * API key
     *
     * @var string
     */
    public $apiKey = '';

    /**
     * Max risk score
     *
     * @var string
     */
    public $maxRiskScore = 0;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ 'apiKey', 'string' ],
            [ 'apiKey', 'default', 'value' => '' ],

            [ 'maxRiskScore', 'integer' ],
            [ 'maxRiskScore', 'default', 'value' => 0 ],
        ];
    }
}
