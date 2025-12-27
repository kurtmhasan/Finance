<template>
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h2 class="mb-4">Hisse Bilgisi Sorgula</h2>

        <div class="input-group mb-3">
            <input
                v-model="symbolInput"
                @keyup.enter="fetchStockDetails"
                type="text"
                class="form-control"
                placeholder="Örn: THYAO"
            >
            <button @click="fetchStockDetails" class="btn btn-primary" :disabled="loading">
                {{ loading ? 'Aranıyor...' : 'Getir' }}
            </button>
        </div>

        <div v-if="stockName" class="alert alert-success mt-3">
            <strong>Hisse Adı:</strong> {{ stockName }}<br>
            <div class="mb-3">
                <label class="form-label">Satın Alınacak Miktar</label>
                <input
                    v-model.number="quantity"
                    type="number"
                    class="form-control"
                    placeholder="0"
                    min="1"
                >
                <br>
                <button v-if="quantity" @click="buyStock" class="btn btn-success w-100 mt-3" >
                    satın al
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// State Tanımları
const symbolInput = ref('');    // Input'a bağlanan veri
const stockName = ref(null);    // DB'den dönecek isim
const loading = ref(false);     // Yüklenme durumu
const errorMessage = ref('');   // Hata mesajı
const quantity = ref();

const fetchStockDetails = async () => {
    // Boş input kontrolü
    if (!symbolInput.value) {
        errorMessage.value = "Lütfen bir sembol girin.";
        return;
    }

    loading.value = true;
    errorMessage.value = '';
    stockName.value = null;

    try {
        // Laravel API'sine POST isteği
        const response = await axios.post('/getStockName', {
            symbol: symbolInput.value.toUpperCase() // Harf büyüterek gönder
        });

        // Başarılı yanıt: Laravel'den { name: '...' } bekliyoruz
        stockName.value = response.data.name;
    } catch (error) {
        // Hata yönetimi
        if (error.response && error.response.status === 404) {
            errorMessage.value = "Hisse bulunamadı.";
        } else {
            errorMessage.value = "Sunucu ile iletişim kurulamadı.";
        }
        console.error("Hata detayı:", error);
    } finally {
        loading.value = false;
    }
};

const buyStock = async () => {
    try {
        const response = await axios.post('/api/buyStock',{
            symbol: symbolInput.value,
            quantity: quantity.value
        });
    }catch (error){

    }
};
</script>
