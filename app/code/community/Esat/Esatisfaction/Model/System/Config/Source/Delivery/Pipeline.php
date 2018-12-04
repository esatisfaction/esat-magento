<?php 
class Esat_Esatisfaction_Model_System_Config_Source_Delivery_Pipeline
{
	public function toOptionArray()
	{
		$esat_helper 	= Mage::helper('esatisfaction/data');	
		$token 			= $esat_helper->getToken();
		$application_id = $esat_helper->getApplicationId();
		
		if(!empty($token) && !empty($application_id)){	
			$questionnaire_id = $esat_helper->getDeliveryQuestionnaireId();
			
			if(!empty($questionnaire_id)){				
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, "https://api.e-satisfaction.com/v3.0/q/questionnaire/".$questionnaire_id."/pipeline");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				  "Content-Type: application/json",
				  "Accept: application/json",
				  "esat-auth: ".$token
				));

				$response = curl_exec($ch);
				curl_close($ch);
				$response_data = json_decode($response,true);
				
				if($httpcode == 200){	
					$pipelines = array();
					foreach($response_data as $result){
						$pipelines[] = array(
							'value' => $result['pipeline_id'], 
							'label' => $result['title']
						);
					}
				}else{
					$pipelines[] = array(
						'value' => 0, 
						'label' => $response_data['message']
					);
				}
			}else{
				$pipelines[] = array(
					'value' => 0, 
					'label' => 'You must first select a Delivery Questionnaire'
				);
			}
		}else{
			$pipelines[] = array(
				'value' => 0, 
				'label' => 'You must give Authentication Token & Application ID'
			);
		}
		
		return $pipelines;
	}
}
?>