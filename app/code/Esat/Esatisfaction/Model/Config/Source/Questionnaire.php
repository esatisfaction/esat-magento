<?php

namespace Esat\Esatisfaction\Model\Config\Source;

use Esat\Esatisfaction\Helper\Data;
use Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Class Questionnaire
 * @package Esat\Esatisfaction\Model\Config\Source
 */
class Questionnaire implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Questionnaire constructor.
     *
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        try {
            $token = $this->helper->getToken();
            $applicationId = $this->helper->getApplicationId();

            // Check for token and application id
            if (empty($token) || empty($applicationId)) {
                throw new InvalidArgumentException('You must give Authentication Token and Application Id');
            }

            // Prepare curl for API call
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.e-satisfaction.com/v3.0/q/questionnaire');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'esat-auth:' . $token,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $responseArray = json_decode($response, true);

            // On error, return message to user
            if ($httpCode != 200) {
                throw new Exception($responseArray['message']);
            }

            // Get API call results
            $questionnaires = [];
            $results = $responseArray['results'];
            foreach ($results as $result) {
                $questionnaires[] = [
                    'value' => $result['questionnaire_id'],
                    'label' => $result['display_name'],
                ];
            }

            return $questionnaires;
        } catch (Throwable $ex) {
            return [
                'value' => 0,
                'label' => $ex->getMessage(),
            ];
        }
    }
}
