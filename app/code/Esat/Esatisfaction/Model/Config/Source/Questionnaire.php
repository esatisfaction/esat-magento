<?php

namespace Esat\Esatisfaction\Model\Config\Source;

use Esat\Esatisfaction\Helper\Data;

class Questionnaire implements \Magento\Framework\Option\ArrayInterface
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

        $questionnaires = [];

        if (!empty($token) && !empty($application_id)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.e-satisfaction.com/v3.0/q/questionnaire');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'esat-auth:'.$token,
            ]);

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $response_data = json_decode($response, true);

            if ($httpcode == 200) {
                $results = $response_data['results'];

                foreach ($results as $result) {
                    $questionnaires[] = [
                        'value' => $result['questionnaire_id'],
                        'label' => $result['display_name'],
                    ];
                }
            } else {
                $questionnaires[] = [
                    'value' => 0,
                    'label' => $response_data['message'],
                ];
            }
        } else {
            $questionnaires[] = [
                'value' => 0,
                'label' => 'You must give Authentication Token & Application ID',
            ];
        }

        return $questionnaires;
    }
}
