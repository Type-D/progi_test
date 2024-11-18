<?php

use PHPUnit\Framework\TestCase;

require_once 'src/bootstrap.php';

class CostCalculatorModelTest extends TestCase
{
    /////////////////////////////////////////////////////
    // Basic buyer fee tests
    /////////////////////////////////////////////////////
  
    // Basic buyer fee for common vehicle = max fee (50$)
    public function testCostCalculatorModelGetBasicBuyerFeeMaxForCommonVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(500000, "common");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 5000;

        $this->assertEquals($expectedResult, $result);
    }
    
    // Basic buyer fee for common vehicle = min fee (10$)
    public function testCostCalculatorModelGetBasicBuyerFeeMinForCommonVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(1000, "common");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 1000;

        $this->assertEquals($expectedResult, $result);
    }

    // Basic buyer fee for common vehicle = 10% of price
    public function testCostCalculatorModelGetBasicBuyerFeeForCommonVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(39800, "common");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 3980;

        $this->assertEquals($expectedResult, $result);
    }    

    // Basic buyer fee for common vehicle = 0$ (shouldn't be possible)
    public function testCostCalculatorModelGetBasicBuyerFeeForCommonVehiclePriceZero()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "common");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }      
    
    // Basic buyer fee for luxury vehicle = max fee (200$)
    public function testCostCalculatorModelGetBasicBuyerFeeMaxForLuxuryVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(500000, "luxury");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 20000;

        $this->assertEquals($expectedResult, $result);
    }
    
    // Basic buyer fee for luxury vehicle = min fee (25$)
    public function testCostCalculatorModelGetBasicBuyerFeeMinForLuxuryVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(1000, "luxury");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 2500;

        $this->assertEquals($expectedResult, $result);
    }

    // Basic buyer fee for luxury vehicle = 10% of price
    public function testCostCalculatorModelGetBasicBuyerFeeForLuxuryVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(39800, "luxury");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 3980;

        $this->assertEquals($expectedResult, $result);
    }    

    // Basic buyer fee for luxury vehicle = 0$
    public function testCostCalculatorModelGetBasicBuyerFeeForLuxuryehiclePriceZero()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "luxury");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }  
    
    // Seller basic buyer fee for vehicle with invalid type = 0$
    public function testCostCalculatorModelGetBasicBuyerFeeForVehicleWithInvalidType()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "invalid");

        $result = $costCalculatorModel->getBasicBuyerFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }      

    /////////////////////////////////////////////////////
    // Association fee tests
    /////////////////////////////////////////////////////  

    // Association fee for vehicle between 1$ and 500$ = 5$
    public function testCostCalculatorModelGetAssociationFeeForVehicleBetween1And500()
    {
        $costCalculatorModel = new CostCalculatorModel(50000, "common");

        $result = $costCalculatorModel->getAssociationFee();

        $expectedResult = 500;

        $this->assertEquals($expectedResult, $result);
    } 

    // Association fee for vehicle more than 500$ up to 1000$ = 10$
    public function testCostCalculatorModelGetAssociationFeeForVehicleMoreThan500UpTo1000()
    {
        $costCalculatorModel = new CostCalculatorModel(100000, "common");

        $result = $costCalculatorModel->getAssociationFee();

        $expectedResult = 1000;

        $this->assertEquals($expectedResult, $result);
    } 

    // Association fee for vehicle more than 500$ up to 1000$ = 15$
    public function testCostCalculatorModelGetAssociationFeeForVehicleMoreThan1000UpTo3000()
    {
        $costCalculatorModel = new CostCalculatorModel(300000, "common");

        $result = $costCalculatorModel->getAssociationFee();

        $expectedResult = 1500;

        $this->assertEquals($expectedResult, $result);
    } 

    // Association fee for vehicle more than 3000$ = 20$
    public function testCostCalculatorModelGetAssociationFeeForVehicleMoreThan3000()
    {
        $costCalculatorModel = new CostCalculatorModel(5000000, "common");

        $result = $costCalculatorModel->getAssociationFee();

        $expectedResult = 2000;

        $this->assertEquals($expectedResult, $result);
    }     

    // Association fee for vehicle with price 0$ = 0$
    public function testCostCalculatorModelGetAssociationFeeForVehiclePriceZero()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "common");

        $result = $costCalculatorModel->getAssociationFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    } 

    // Seller association fee for vehicle with invalid type = 0$
    public function testCostCalculatorModelGetAssociationFeeForVehicleWithInvalidType()
    {
        $costCalculatorModel = new CostCalculatorModel(1000, "invalid");

        $result = $costCalculatorModel->getAssociationFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }        

    /////////////////////////////////////////////////////
    // Seller's special fee tests
    /////////////////////////////////////////////////////   
    
    // Seller special fee for common vehicle = 2% of vehicle price
    public function testCostCalculatorModelGetSellerSpecialFeeForCommonVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(50000, "common");

        $result = $costCalculatorModel->getSellerSpecialFee();

        $expectedResult = 1000;

        $this->assertEquals($expectedResult, $result);
    } 
    
    // Seller special fee for luxury vehicle = 4% of vehicle price
    public function testCostCalculatorModelGetSellerSpecialFeeForLuxuryVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(50000, "luxury");

        $result = $costCalculatorModel->getSellerSpecialFee();

        $expectedResult = 2000;

        $this->assertEquals($expectedResult, $result);
    }  

    // Seller special fee for 0$ vehicle = 0$
    public function testCostCalculatorModelGetSellerSpecialFeeForVehiclePriceZero()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "luxury");

        $result = $costCalculatorModel->getSellerSpecialFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    } 
    
    // Seller special fee for vehicle with invalid type = 0$
    public function testCostCalculatorModelGetSellerSpecialFeeFeeForVehicleWithInvalidType()
    {
        $costCalculatorModel = new CostCalculatorModel(1000, "invalid");

        $result = $costCalculatorModel->getSellerSpecialFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }        

    /////////////////////////////////////////////////////
    // Storage fee tests
    /////////////////////////////////////////////////////

    // Storage fee for common vehicle = 100$
    public function testCostCalculatorModelGetStorageFeeForCommonVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(39800, "common");

        $result = $costCalculatorModel->getStorageFee();

        $expectedResult = 10000;

        $this->assertEquals($expectedResult, $result);
    }

    // Storage fee for luxury vehicle = 100$
    public function testCostCalculatorModelGetStorageFeeForLuxuryVehicle()
    {
        $costCalculatorModel = new CostCalculatorModel(100000, "luxury");

        $result = $costCalculatorModel->getStorageFee();

        $expectedResult = 10000;

        $this->assertEquals($expectedResult, $result);
    }  

    // Storage fee for 0$ vehicle = 0$
    public function testCostCalculatorModelGetStorageFeeForVehicleWithPriceZero()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "common");

        $result = $costCalculatorModel->getStorageFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }   
    
    // Storage fee for vehicle with invalid type = 0$
    public function testCostCalculatorModelGetStorageFeeForVehicleWithInvalidType()
    {
        $costCalculatorModel = new CostCalculatorModel(1000, "invalid");

        $result = $costCalculatorModel->getStorageFee();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }      
    
    /////////////////////////////////////////////////////
    // Total fees tests
    /////////////////////////////////////////////////////  
    
    // Total fees should equal all fees added
    public function testCostCalculatorModelGetTotalFeesForVehicleShouldTotalAllFeesAdded()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "common");

        $result = $costCalculatorModel->getTotalFees();

        $expectedResult = $costCalculatorModel->getBasicBuyerFee() + 
                            $costCalculatorModel->getStorageFee() + 
                            $costCalculatorModel->getSellerSpecialFee() + 
                            $costCalculatorModel->getAssociationFee();

        $this->assertEquals($expectedResult, $result);
    } 
    
    // Total fees for 0$ vehicle = 0$
    public function testCostCalculatorModelGetTotalFeesForVehicleWithPriceZero()
    {
        $costCalculatorModel = new CostCalculatorModel(0, "common");

        $result = $costCalculatorModel->getTotalFees();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    } 

    // Total fees for vehicle with invalid type = 0$
    public function testCostCalculatorModelGetTotalFeesForVehicleWithInvalidType()
    {
        $costCalculatorModel = new CostCalculatorModel(1000, "invalid");

        $result = $costCalculatorModel->getTotalFees();

        $expectedResult = 0;

        $this->assertEquals($expectedResult, $result);
    }     
}