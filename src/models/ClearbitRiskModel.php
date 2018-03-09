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
class ClearbitRiskModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Risk score 0 - 100
     *
     * @var integer
     */
    public $riskScore = 0;

    /**
     * Risk level low, medium, or high
     *
     * @var string
     */
    public $level = 'low';

    /**
     * Live
     *
     * @var boolean
     */
    public $live = false;

    /**
     * Email results
     *
     * @var mixed
     */
    public $email = [];

    /**
     * Address results
     *
     * @var mixed
     */
    public $address = [];

    /**
     * IP results
     *
     * @var mixed
     */
    public $ip = [];

    /*
     * {
  "id": "593e0413-fa21-461b-adb5-349283731051",
  "live": false,
  "email": {
    "valid": true,
    "socialMatch": false,
    "companyMatch": true,
    "nameMatch": null,
    "disposable": true,
    "freeProvider": false,
    "blacklisted": true
  },
  "address": {
    "geoMatch": null
  },
  "ip": {
    "proxy": false,
    "geoMatch": null,
    "blacklisted": true,
    "rateLimited": null
  },
  "risk": {
    "level": "low",
    "score": 0
  }
}
     */

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ 'someAttribute', 'string' ],
            [ 'someAttribute', 'default', 'value' => 'Some Default' ],
        ];
    }
}
