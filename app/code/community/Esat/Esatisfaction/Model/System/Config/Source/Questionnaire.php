<?php

/**
 * Class Esat_Esatisfaction_Model_System_Config_Source_Questionnaire
 */
class Esat_Esatisfaction_Model_System_Config_Source_Questionnaire
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helperData = Mage::helper('esatisfaction/data');
        $token = $helperData->getToken();
        $applicationId = $helperData->getApplicationId();

        // Validate token and application id
        if (empty($token) || empty($applicationId)) {
            return [
                [
                    'value' => 0,
                    'label' => 'You must provide an Application Id and an Authentication Token',
                ],
            ];
        }

        // Filter questionnaires by application id
        $parameters = [
            'filter_by' => [
                'EQUAL' => [
                    'OwnerApplicationId' => $applicationId,
                ],
            ],
        ];

        // Gt questionnaires from API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://api.e-satisfaction.com/v3.1/q/questionnaire?%s', http_build_query($parameters)));
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
        $response_data = json_decode($response, true);

        // Get API error message
        if ($httpCode != 200) {
            return [
                [
                    'value' => 0,
                    'label' => $response_data['message'],
                ],
            ];
        }

        // Get questionnaires
        $questionnaires = [];
        $results = $response_data['results'];
        foreach ($results as $result) {
            $questionnaires[] = [
                'value' => $result['questionnaire_id'],
                'label' => $result['display_name'],
            ];
        }

        return $questionnaires;
    }
}
