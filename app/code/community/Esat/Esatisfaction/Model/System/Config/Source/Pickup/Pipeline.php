<?php

/**
 * Class Esat_Esatisfaction_Model_System_Config_Source_Pickup_Pipeline
 */
class Esat_Esatisfaction_Model_System_Config_Source_Pickup_Pipeline
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

        // Check pickup questionnaire
        $questionnaireId = $helperData->getPickupQuestionnaireId();
        if (empty($questionnaireId)) {
            return [
                [
                    'value' => 0,
                    'label' => 'You must first select a Pickup Questionnaire',
                ],
            ];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://api.e-satisfaction.com/v3.1/q/questionnaire/%s/pipeline', $questionnaireId));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'esat-auth: ' . $token,
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

        // Get pipelines
        $pipelines = [];
        foreach ($response_data as $result) {
            $pipelines[] = [
                'value' => $result['pipeline_id'],
                'label' => $result['title'],
            ];
        }

        return $pipelines;
    }
}
