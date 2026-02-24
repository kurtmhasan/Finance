<template>
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="max-width: 450px;">
        <div class="card-header bg-dark py-3 text-center">
            <h6 class="text-white mb-0 fw-bold letter-spacing-1">HİSSE SORGULAMA </h6>
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
                <div v-if="stock" class="modal-overlay" @click.self="closeStock">

                    <div class="modal-card border-0 rounded-4 bg-white shadow-2xl position-relative">

                        <button
                            @click="closeStock"
                            class="btn-close-custom"
                            title="Kapat"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stock-badge me-2">{{ stock.symbol }}</div>
                                <div class="fw-bold text-dark truncate">{{ stock.full_name }}</div>
                            </div><br>

                            <div class="price-section mb-4">
                                <div class="text-muted small fw-bold">GÜNCEL FİYAT:
                                    {{ stock.price }} <span class="fs-6 text-secondary">{{ stock.currency }}</span>
                                </div><br>
                                <div class="text-muted small fw-bold">
                                    günlük en düşük fiyat: {{stock.day_low}}
                                </div>

                                <div class="text-muted small fw-bold">
                                    günlük en yüksek fiyat: {{stock.day_high}}
                                </div>
                            </div>

                            <div class="action-area">
                                <label class="form-label x-small fw-bold text-muted">ALINACAK ADET</label>
                                <input
                                    v-model.number="quantity"
                                    type="number"
                                    class="form-control form-control-lg mb-3 custom-input"
                                    placeholder="0"
                                >

                                <div v-if="quantity > 0" class="total-box d-flex justify-content-between p-2 rounded-2 mb-3">
                                    <span class="text-muted small">Tahmini Toplam:</span>
                                    <span class="fw-bold text-success">{{ (quantity * stock.price).toFixed(2) }} {{ stock.currency }}</span>
                                </div>

                                <button
                                    :disabled="!quantity || quantity <= 0"
                                    @click.prevent="buyStock"
                                    class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm buy-button"
                                >
                                    Siparişi Onayla
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
/* Arka planı karartma ve ortalama */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.6); /* Modern lacivert-siyah transparan */
    backdrop-filter: blur(6px); /* Arka planı bulanıklaştırır */
    display: flex;
    align-items: center;    /* Dikeyde tam merkez */
    justify-content: center; /* Yatayda tam merkez */
    z-index: 9999;
    padding: 20px;
}

/* Kartın kendisi */
.modal-card {
    width: 100%;
    max-width: 400px;
    min-height: 300px;
    background: white;
    transform: translateY(0);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Özel Kapatma Butonu */
.btn-close-custom {
    position: absolute;
    top: -15px;
    right: -15px;
    width: 35px;
    height: 35px;
    background: #ff4757;
    color: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(255, 71, 87, 0.4);
    cursor: pointer;
    transition: all 0.2s;
    z-index: 10001;
}

.btn-close-custom:hover {
    background: #ff6b81;
    transform: scale(1.1) rotate(90deg);
}

/* Diğer Detaylar */
.stock-badge {
    background: #f1f5f9;
    color: #475569;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 0.8rem;
}

.total-box {
    background: #f8fafc;
    border: 1px dashed #e2e8f0;
}

.buy-button {
    background: #2ed573;
    border: none;
}

.buy-button:hover {
    background: #26af5f;
}

/* Animasyonlar */
.fade-slide-enter-active, .fade-slide-leave-active {
    transition: opacity 0.3s, transform 0.3s;
}
.fade-slide-enter-from, .fade-slide-leave-to {
    opacity: 0;
    transform: scale(0.9);
}
</style>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

// State Tanımları
const symbolInput = ref('');    // Input'a bağlanan veri
const stock = ref(null);    // apiden controllera çektiğimiz data
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
    stock.value = null;

    try {
        // Laravel API'sine POST isteği
        const response = await axios.post('/api/getStockName', {
            symbol: symbolInput.value.toUpperCase() // Harf büyüterek gönder
        });

        // baackendden gelen veriyi stock değikeninin değişkenine atadık
        stock.value = response.data;
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
            quantity: quantity.value,

        });
        stock.value=null
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
    quantity.value = null
};
function closeStock() {
    stock.value = null
    quantity.value = 0
}
</script>
