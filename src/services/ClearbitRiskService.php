<?php
/**
 * Clearbit Risk plugin for Craft CMS 3.x
 *
 * Detect bad actors, identify disposable email addresses, stop spam signups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\clearbitrisk\services;

use craft\elements\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use superbig\clearbitrisk\ClearbitRisk;

use Craft;
use craft\base\Component;

/**
 * @author    Superbig
 * @package   ClearbitRisk
 * @since     1.0.0
 */
class ClearbitRiskService extends Component
{
    // Public Methods
    // =========================================================================

    public $apiKey = 'string';

    public $calculateEndpoint = 'https://risk.clearbit.com/v1/calculate';
    public $flagEndpoint      = 'https://risk.clearbit.com/v1/flag';

    /*
     * @return mixed
     */
    public function onSignup (User $user)
    {
        // Check our Plugin's settings for `someAttribute`
        $settings     = ClearbitRisk::$plugin->getSettings();
        $this->apiKey = $settings->apiKey;

        if ( empty($this->apiKey) ) {
            return null;
        }

        $data = [
            'email'       => $user->email,
            'given_name'  => $user->firstName,
            'family_name' => $user->lastName,
            'ip'          => Craft::$app->getRequest()->getUserIP(),
            //'country_code' => 'NO',
            //'zip_code' => '0470',
        ];

        $result = $this->getRisk($data);
    }

    /**
     * @param $data
     */
    public function getRisk ($data)
    {
        $client = new Client();

        try {
            $response = $client->get($this->calculateEndpoint, [
                'headers' => [ 'Authorization' => 'Bearer ' . $this->apiKey ],
                'query'   => $data,
            ]);

            if ( $response ) {
                Craft::info(json_decode($response->getBody()), 'clearbit-risk');
            }
        }
        catch (RequestException $e) {
            Craft::error($e->getMessage(), 'clearbit-risk');
        }
    }

    // TODO: https://dashboard.clearbit.com/docs?shell#risk-api-flagging
    // TODO: Add user action
    public function flagUser (User $user)
    {
        $client = new Client();

        try {
            $data = [
                // Type of flag: either spam, chargeback, or other.
                'type'  => 'spam',
                'email' => $user->email,
                'ip'    => $user->lastLoginAttemptIp,
            ];

            $response = $client->get($this->flagEndpoint, [
                'headers' => [ 'Authorization' => 'Bearer ' . $this->apiKey ],
                'query'   => $data,
            ]);

            if ( $response ) {
                Craft::info(json_decode($response->getBody()), 'clearbit-risk');
            }
        }
        catch (\HttpException $e) {
            Craft::error($e->getMessage(), 'clearbit-risk');
        }
    }
}
