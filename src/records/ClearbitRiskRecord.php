<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk\records;

use superbig\clearbitrisk\ClearbitRisk;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 */
class ClearbitRiskRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clearbitrisk_risk}}';
    }
}
