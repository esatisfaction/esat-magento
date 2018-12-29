<?php

namespace Esat\Esatisfaction\Model\Config\Source\Delivery;

use Esat\Esatisfaction\Helper\Data;
use Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Class Pipeline
 * @package Esat\Esatisfaction\Model\Config\Source\Delivery
 */
class Pipeline implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Pipeline constructor.
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

            // Get questionnaire id
            $questionnaireId = $this->helper->getDeliveryQuestionnaireId();
            if (empty($questionnaireId)) {
                throw new InvalidArgumentException('You must first select a Delivery Questionnaire');
            }

            // Prepare curl for API call
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, sprintf('https://api.e-satisfaction.com/v3.0/q/questionnaire/%s/pipeline', $questionnaireId));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'esat-auth: ' . $token,
            ]);

            // Execute API call
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $responseArray = json_decode($response, true);

            // On error, return message to user
            if ($httpCode != 200) {
                throw new Exception($responseArray['message']);
            }

            // Gather response data
            $optionArray = [];
            foreach ($responseArray as $result) {
                $optionArray[] = [
                    'value' => $result['pipeline_id'],
                    'label' => $result['title'],
                ];
            }

            return $optionArray;
        } catch (Throwable $ex) {
            return [
                'value' => 0,
                'label' => $ex->getMessage(),
            ];
        }
    }
}
