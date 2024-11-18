<?php
declare(strict_types=1);

require_once __DIR__ . "/../../Helpers/Enums.php";

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class CostCalculatorController extends BaseController
{
    // Cost calculator endpoint
    public function calculateCosts()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                // Vehicle price not set
                if (!isset($arrQueryStringParams["vehiclePrice"])) {
                    $this->sendOutput(json_encode(array("error" => "Vehicle price is mandatory")), 
                        array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
                    );
                    return;  
                }

                // Vehicle type not set
                if (!isset($arrQueryStringParams["vehicleType"])) {
                    $this->sendOutput(json_encode(array("error" => "Vehicle type is mandatory")), 
                        array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
                    );
                    return;  
                }

                // Invalid vehicle type
                if (array_search($arrQueryStringParams["vehicleType"], array_column(VehicleType::cases(), 'value')) === false) {
                    $this->sendOutput(json_encode(array("error" => "Vehicle type must be either 'common' or 'luxury'!")), 
                        array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
                    );
                    return;                      
                }

                $costCalculatorModel = new CostCalculatorModel(intval($arrQueryStringParams["vehiclePrice"]), $arrQueryStringParams["vehicleType"]);
                $formattedFees = json_encode([
                    "storageFee" => $costCalculatorModel->getStorageFee(),
                    "basicBuyerFee" => $costCalculatorModel->getBasicBuyerFee(),
                    "sellerSpecialFee" => $costCalculatorModel->getSellerSpecialFee(),
                    "associationFee" => $costCalculatorModel->getAssociationFee(),
                    "totalFees" => $costCalculatorModel->getTotalFees(),
                ]);
        
                // Send output
                $this->sendOutput(
                    $formattedFees,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );    
            } catch (Error $e) {
                // Unexpected error
                $this->sendOutput(json_encode(array("error" => "Something went wrong! Please contact support.")), 
                array("Content-Type: application/json", "HTTP/1.1 500 Internal Server Error")
                ); 
            }
        } else {
            // Only get is supported
            $this->sendOutput(json_encode(array("error" => "Method not supported")), 
                array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
            );
        }
    }
}