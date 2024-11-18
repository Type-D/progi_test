<?php
class CostCalculatorController extends BaseController
{
    // Cost calculator endpoint
    public function calculateCosts()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            // try {
                // Make the error as specific as possible
                if (!isset($arrQueryStringParams["vehiclePrice"])) {
                    $this->sendOutput(json_encode(array("error" => "Vehicle price is mandatory")), 
                        array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
                    );
                    return;  
                }

                if (!isset($arrQueryStringParams["vehicleType"])) {
                    $this->sendOutput(json_encode(array("error" => "Vehicle type is mandatory")), 
                        array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
                    );
                    return;  
                }
                $costCalculatorModel = new CostCalculatorModel($arrQueryStringParams["vehiclePrice"], $arrQueryStringParams["vehicleType"]);
                $formattedFees = json_encode([
                    "storageFee" => $costCalculatorModel->getStorageFee(),
                    "basicBuyerFee" => $costCalculatorModel->getBasicBuyerFee(),
                    "specialSellerFee" => $costCalculatorModel->getSpecialSellerFee(),
                    "associationFee" => $costCalculatorModel->getAssociationFee(),
                    // "totalFees" => $costCalculatorModel->getTotalFees(),
                ]);
        
                // Send output
                $this->sendOutput(
                    $formattedFees,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );    
                /*            
            } catch (Error $e) {
                // Unexpected error
                $this->sendOutput(json_encode(array("error" => "Something went wrong! Please contact support.")), 
                array("Content-Type: application/json", "HTTP/1.1 500 Internal Server Error")
                ); 
            } */
        } else {
            // Only get is supported
            $this->sendOutput(json_encode(array("error" => "Method not supported")), 
                array("Content-Type: application/json", "HTTP/1.1 422 Unprocessable Entity")
            );
        }
    }
}