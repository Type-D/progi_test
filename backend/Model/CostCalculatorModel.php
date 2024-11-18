<?php 
declare(strict_types=1);

require("Helpers/Enums.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

const EPSILON = 0.00001;

// Strictly speaking, can we say this is REALLY a model?
class CostCalculatorModel {
    const STORAGE_FEE = 10000; // Storage fee is always 100$

    // Base properties
    protected int $vehiclePrice;
    protected string $vehicleType;
    
    // Calculated fields (everything is int to avoid floating point shenanigans)
    protected int $basicBuyerFee = 0;
    protected int $specialSellerFee = 0;
    protected int $associationFee = 0; 

    // Could've been handled here, but then this wouldn't demonstrate a computed() in the frontend!
    // protected int $totalFees = 0;

    // Since both properties are mandatory, let's simplify things and pass them to the constructor right away
    public function __construct(int $vehiclePrice, string $vehicleType) {
        $this->vehiclePrice = $vehiclePrice;
        $this->vehicleType = $vehicleType;
        
        $this->basicBuyerFee = $this->calculateBasicBuyerFee();
        $this->specialSellerFee = $this->calculateSellerSpecialFee();
        $this->associationFee = $this->calculateAssociationFee();

        // $this->totalFees = $this->calculateTotalFees();
    }    
    
    // Getters
    public function getStorageFee(): int {
        // It'd make sense that a 0$ vehicle doesn't yield any fees
        if(abs($this->vehiclePrice - 0) < EPSILON) {
            return 0;
        }
        return CostCalculatorModel::STORAGE_FEE;
    }

    public function getBasicBuyerFee(): int {
        return $this->basicBuyerFee;
    }

    public function getSpecialSellerFee(): int {
        return $this->specialSellerFee;
    }

    public function getAssociationFee(): int {
        return $this->associationFee;
    }    
    
    // public function getTotalFees(): int {
    //    return $this->totalFees;
    // }

    protected function calculateBasicBuyerFee(): int {
        // It'd make sense that a 0$ vehicle doesn't yield any fees
        if($this->vehiclePrice === 0) {
            return 0;
        }
        $minFee = $this->vehicleType === VehicleType::COMMON->value ? 1000 : 2500; // Min fee common vs luxury
        $maxFee = $this->vehicleType === VehicleType::COMMON->value ? 5000 : 20000; // Max fee common vs luxury

        $rawBuyerFee = intval(round($this->vehiclePrice * 0.1)); // 10% of vehicle price

        if ($rawBuyerFee < $minFee) {
            return $minFee;
        }

        if ($rawBuyerFee > $maxFee) {
            return $maxFee;
        }

        return $rawBuyerFee;
    }

    protected function calculateSellerSpecialFee(): int {
        // It'd make sense that a 0$ vehicle doesn't yield any fees
        if($this->vehiclePrice === 0) {
            return 0;
        }

        // Common = 2% of vehicle price
        if ($this->vehicleType === VehicleType::COMMON->value) {
            return intval(round($this->vehiclePrice * 0.02));
        }

        // Luxury = 4% of vehicle price
        if ($this->vehicleType === VehicleType::LUXURY->value) {
            return intval(round($this->vehiclePrice * 0.04));
        }

        // Should not be possible anyway!
        throw new Exception('Vehicle type should be either common or luxury');
    }

    protected function calculateAssociationFee(): int {
        if ($this->vehiclePrice >= 1 && $this->vehiclePrice <= 50000) {
            return 500;
        }
        if ($this->vehiclePrice > 50000 && $this->vehiclePrice <= 100000) {
            return 1000;
        }        
        if ($this->vehiclePrice > 100000 && $this->vehiclePrice <= 300000) {
            return 1500;
        }                
        if ($this->vehiclePrice > 300000) {
            return 2000;
        }

        return 0; // A vehicle under 1$? Sounds suspicious! Should it throw an error?
    }

    // protected function calculateTotalFees(): float {
    //    return CostCalculatorModel::STORAGE_FEE + $this->basicBuyerFee + $this->specialSellerFee + $this->associationFee;
    // }
}
?>