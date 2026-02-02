<template>
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="max-width: 450px;">
        <div class="card-header bg-dark py-3 text-center">
            <h6 class="text-white mb-0 fw-bold letter-spacing-1">HİSSE SORGULAMA</h6>
        </div>

        <div class="card-body p-4 bg-white">
            <div class="d-flex gap-2 mb-4">
                <input
                    v-model="symbolInput"
                    @keyup.enter="fetchStockDetails"
                    type="text"
                    class="form-control custom-input"
                    placeholder="Sembol (örn: THYAO)"
                >
                <button
                    @click="fetchStockDetails"
                    class="btn btn-modern-green px-4 shadow-sm"
                    :disabled="loading"
                >
                    <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                    {{ loading ? '...' : 'Getir' }}
                </button>
            </div>

            <transition name="fade-slide">
                <div v-if="stockName" class="p-3 rounded-3 border bg-light shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted fw-bold">HİSSE:</small>
                        <span class="fw-bold text-dark fs-5">{{ stockName }}</span>
                    </div>

                    <div class="mt-2">
                        <label class="form-label x-small fw-bold text-secondary">ADET</label>
                        <input
                            v-model.number="quantity"
                            type="number"
                            class="form-control custom-input mb-3"
                            placeholder="0"
                        >

                        <button
                            v-if="quantity > 0"
                            @click.prevent="buyStock"
                            class="btn btn-modern-green w-100 py-2 fw-bold text-uppercase shadow-sm"
                        >
                            Satın Al
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
.letter-spacing-1 { letter-spacing: 1px; }
.x-small { font-size: 0.75rem; }

/* Yeşil Buton ve Hover Efekti */
.btn-modern-green {
    background-color: #198754;
    color: #ffffff !important;
    border: none;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-modern-green:hover:not(:disabled) {
    background-color: #157347; /* Daha koyu yeşil */
    transform: translateY(-2px); /* Yukarı hafif esneme */
    box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3); /* Yeşil gölge */
}

.btn-modern-green:active {
    transform: translateY(0);
}

/* Input Odaklanma */
.custom-input {
    border: 2px solid #eee;
}

.custom-input:focus {
    border-color: #198754;
    box-shadow: none;
}

/* Animasyonlar */
.fade-slide-enter-active {
    transition: all 0.3s ease-out;
}
.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(10px);
}
</style>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

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
        const response = await axios.post('/buyStock',{
            symbol: symbolInput.value,
            quantity: quantity.value
        });
        Swal.fire({
            icon: 'success',
            title: 'İşlem Başarılı!',
            text: `${quantity.value} adet ${symbolInput.value} portföyünüze eklendi.`,
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#198754' // Bootstrap Success rengi
        });
    }catch (error){
        Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: error.response?.data?.message || 'Bir sorun oluştu.',
            confirmButtonText: 'Tekrar Dene'
        });
    }
};
</script>
