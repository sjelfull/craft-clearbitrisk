<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk\migrations;

use superbig\clearbitrisk\ClearbitRisk;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp ()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ( $this->createTables() ) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown ()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables ()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%clearbitrisk_risk}}');
        if ( $tableSchema === null ) {
            $tablesCreated = true;
            $this->createTable(
                '{{%clearbitrisk_risk}}',
                [
                    'id'          => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid'         => $this->uid(),
                    'siteId'      => $this->integer()->notNull(),

                    'riskScore' => $this->integer()->defaultValue(0),
                    'riskLevel' => $this->enum('riskLevel', [ 'low', 'medium', 'high' ])->defaultValue('low'),
                    'live'      => $this->boolean()->defaultValue(false),
                    'email'     => $this->text()->null()->defaultValue(null),
                    'address'   => $this->text()->null()->defaultValue(null),
                    'ip'        => $this->text()->null()->defaultValue(null),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes ()
    {
        $this->createIndex(
            $this->db->getIndexName(
                '{{%clearbitrisk_risk}}',
                'some_field',
                true
            ),
            '{{%clearbitrisk_risk}}',
            'some_field',
            true
        );
        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * @return void
     */
    protected function addForeignKeys ()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%clearbitrisk_risk}}', 'siteId'),
            '{{%clearbitrisk_risk}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    protected function insertDefaultData ()
    {
    }

    /**
     * @return void
     */
    protected function removeTables ()
    {
        $this->dropTableIfExists('{{%clearbitrisk_risk}}');
    }
}
