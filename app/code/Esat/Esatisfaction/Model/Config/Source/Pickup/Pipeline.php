<?php

namespace Esat\Esatisfaction\Model\Config\Source\Pickup;

use Esat\Esatisfaction\Helper\Data;

class Pipeline implements \Magento\Framework\Option\ArrayInterface
{
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function toOptionArray()
    {
        $token = $this->helper->getToken();
        $application_id = $this->helper->getApplicationId();

        $pipelines = [];
        if (!empty($token) && !empty($application_id)) {
            $questionnaire_id = $this->helper->getPickupQuestionnaireId();

            if (!empty($questionnaire_id)) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.e-satisfaction.com/v3.0/q/questionnaire/'.$questionnaire_id.'/pipeline');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);

                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                  'Content-Type: application/json',
                  'Accept: application/json',
                  'esat-auth: '.$token,
                ]);

                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $response_data = json_decode($response, true);

                if ($httpcode == 200) {
                    foreach ($response_data as $result) {
                        $pipelines[] = [
                            'value' => $result['pipeline_id'],
                            'label' => $result['title'],
                        ];
                    }
                } else {
                    $pipelines[] = [
                        'value' => 0,
                        'label' => $response_data['message'],
                    ];
                }
            } else {
                $pipelines[] = [
                    'value' => 0,
                    'label' => 'You must first select a Pickup Questionnaire',
                ];
            }
        } else {
            $pipelines[] = [
                'value' => 0,
                'label' => 'You must give Authentication Token & Application ID',
            ];
        }

        return $pipelines;
    }
}
