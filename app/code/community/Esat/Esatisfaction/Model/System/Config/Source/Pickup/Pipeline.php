<?php

class Esat_Esatisfaction_Model_System_Config_Source_Pickup_Pipeline
{
    public function toOptionArray()
    {
        $esat_helper = Mage::helper('esatisfaction/data');
        $token = $esat_helper->getToken();
        $application_id = $esat_helper->getApplicationId();
		$pipelines = [];
		
        if (!empty($token) && !empty($application_id)) {
            $questionnaire_id = $esat_helper->getPickupQuestionnaireId();

            if (!empty($questionnaire_id)) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.e-satisfaction.com/v3.0/q/questionnaire/'.$questionnaire_id.'/pipeline');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);

                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                  'Content-Type: application/json',
                  'Accept: application/json',
                  'esat-auth: '.$token
                ]);

                $response = curl_exec($ch);
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $response_data = json_decode($response, true);

                if ($httpcode == 200) {
                    foreach ($response_data as $result) {
                        $pipelines[] = [
                            'value' => $result['pipeline_id'],
                            'label' => $result['title']
                        ];
                    }
                } else {
                    $pipelines[] = [
                        'value' => 0,
                        'label' => $response_data['message']
                    ];
                }
            } else {
                $pipelines[] = [
                    'value' => 0,
                    'label' => 'You must first select a Pickup Questionnaire'
                ];
            }
        } else {
            $pipelines[] = [
                'value' => 0,
                'label' => 'You must give Authentication Token & Application ID'
            ];
        }

        return $pipelines;
    }
}