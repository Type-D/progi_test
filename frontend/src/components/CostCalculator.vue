<script setup lang="ts">
  import { computed, ref, watch } from 'vue';
  import axios from 'axios';

  type Fees = {
    storageFee: number;
    basicBuyerFee: number
    specialSellerFee: number;
    associationFee: number;
  };

  type Vehicle = {
    basePrice: number;
    type: "common" | "luxury";
  }

  const vehicle = ref<Vehicle>({
    basePrice: 0,
    type: "common",
  });

  const fees = ref<Fees>({
    storageFee: 0,
    basicBuyerFee: 0,
    specialSellerFee: 0,
    associationFee: 0,
  });

  // Vehicle price is still a float
  const totalFees = computed(() =>
    (
      (vehicle.value.basePrice * 100) +
      fees.value.storageFee +
      fees.value.basicBuyerFee +
      fees.value.specialSellerFee +
      fees.value.associationFee | 0
    )
  )

  const errorMessage = computed(() => {
    if (parseFloat(vehicle.value.basePrice) !== vehicle.value.basePrice) {
      return "Invalid price! Must be any amount greater than 0.";
    }
    return "";
  });

  const limitInput = async() => {
    // No negative amounts
    if (parseFloat(vehicle.value.basePrice) < 0) {
      vehicle.value.basePrice = Math.abs(parseFloat(vehicle.value.basePrice));
      return;
    }
    // Limit to 2 decimals (if any!)
    if (vehicle.value.basePrice % 1 !== 0 && vehicle.value.basePrice.toString().split(".")[1].length > 2) {
      vehicle.value.basePrice = Math.floor(vehicle.value.basePrice * 100) / 100;
      return;
    }
  }

  const updateFees = async() => {
    const { data } = await axios.get(
      "http://localhost/cost-calculator/calculateCosts",
      {
        params: {
          vehiclePrice: parseInt(vehicle.value.basePrice * 100) | 0, // Use cents to avoid floating point shenanigans
          vehicleType: vehicle.value.type,
        },
      }
    );
    fees.value = data;
  };

  const formatForDisplay = (value) => {
    return parseFloat(value / 100).toFixed(2);
  }

  // There could've been other ways to trigger the cost calculation, but a watch() on an object ain't half that bad
  // since it covers both property changes at once!
  watch(
    vehicle,
    () => {
      updateFees();
    },
    { deep: true },
  );
</script>

<template>
  <div class="card">
    <h2>Super advanced (not!) vehicle cost calculator&#8482;</h2>
    <div class="fields">
      <div class="label">Vehicle price:</div>
      <div><input type="number" v-model="vehicle.basePrice" maxlength=20 @input="limitInput()"></div>
    </div>
    <div class="fields">
      <div class="label">Vehicle type:</div>
      <div>
        <select v-model="vehicle.type">
          <option value="common">Common</option>
          <option value="luxury">Luxury</option>
        </select>
      </div>
    </div>
    <div class="error">{{ errorMessage }}</div>
    <h3>Fees</h3>
    <div class="fields fees">
      <div class="label">Basic: </div>
      <div>{{ formatForDisplay(fees.basicBuyerFee) }} $</div>
    </div>
    <div class="fields fees">
      <div class="label">Special: </div>
      <div>{{ formatForDisplay(fees.specialSellerFee) }} $</div>
    </div>
    <div class="fields fees">
      <div class="label">Association: </div>
      <div>{{ formatForDisplay(fees.associationFee) }} $</div>
    </div>
    <div class="fields fees">
      <div class="label">Storage: </div>
      <div>{{ formatForDisplay(fees.storageFee) }} $</div>
    </div>
    <div class="fields fees">
      <div class="label">Total: </div>
      <div>{{ formatForDisplay(totalFees) }} $</div>
    </div>
  </div>
</template>

<style>
h3 {
  margin-bottom: 0;
  color: #666;
}

div.card {
  border: 2px solid #999;
  border-radius: 10px;
  padding-top: 0.5vh;
  text-align: left;
}

div.card>h2 {
  color: #666;
}

div.fields {
  margin: 1vh 0;
}

div.fields>div {
  display: inline-block;
}

div.label {
  width: 25%;
}

div.fees {
  padding: 0.5vh 0.5vw;
}

div.fees:nth-child(even) {
  background-color: #ddd;
}

input[type=number], select {
  border-radius: 3px;
  border: 2px solid #999;
  font-size: 1.2em;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
