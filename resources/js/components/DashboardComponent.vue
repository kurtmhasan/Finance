<template>
    <div class=" bg-gray-100 p-4 flex  pt-12">

        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 border border-gray-200 h-fit flex flex-col items-center">

            <h1 class="text-4xl font-black tracking-tighter text-indigo-900 mb-8 uppercase italic text-center">
                KURT BANK
            </h1>

            <div class="w-full flex flex-col items-center mb-8">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 text-center">
                    Mevcut Bakiyeniz
                </p>

                <div v-if="loading" class="h-8 w-48 bg-gray-200 animate-pulse rounded-lg"></div>

                <div v-else-if="walletNumber" class="w-full flex justify-center">
                <span class="inline-flex items-center justify-center text-lg font-mono font-bold text-indigo-700 bg-indigo-50 px-6 py-2 rounded-full border border-indigo-100">
                 {{ bakiye }} ₺
                </span>
                </div>
            </div>

            <div class="w-full flex flex-col items-center mb-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 text-center">
                    Cüzdan Numarası / IBAN
                </p>

                <div v-if="loading" class="h-8 w-48 bg-gray-200 animate-pulse rounded-lg"></div>

                <div v-else-if="walletNumber" class="w-full flex flex-col items-center">
                <span class="inline-flex items-center justify-center text-lg font-mono font-bold text-indigo-700 bg-indigo-50 px-6 py-2 rounded-full border border-indigo-100">
                    {{ walletNumber }}
                </span>

                    <button @click="copyToClipboard" class="mt-4 text-xs font-semibold text-indigo-500 hover:text-indigo-800 transition-colors uppercase tracking-tight text-center">
                        Numarayı Kopyala
                    </button>
                </div>

                <span v-else class="text-red-500 font-bold text-center">Cüzdan bulunamadı.</span>
            </div>

            <transition name="fade">
                <p v-if="copied" class="text-xs text-emerald-600 font-bold italic tracking-tight text-center">
                    ✓ Başarıyla panoya kopyalandı
                </p>
            </transition>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// 1. Reaktif değişkenleri tanımla
const walletNumber = ref(null);
const bakiye = ref(null);
const copied = ref(false);
const loading = ref(true);

// 2. Veri çekme fonksiyonu
const getDashboardData = async () => {
    try {
        // web.php içindeki rotaya istek atıyoruz
        const response = await axios.get('/dashboard-data');

        // ÖNEMLİ: Laravel'den dönen JSON: { "wallet_number": "TR..." }
        // Axios sonucu response.data içinde tutar.
        walletNumber.value = response.data.wallet_number;
        bakiye.value = response.data.balance;
    } catch (error) {
        console.error("Dashboard veri çekme hatası:", error);
    } finally {
        // İşlem bittiğinde yükleniyor durumunu kapat
        loading.value = false;
    }
};

const copyToClipboard = async ()=> {
    try {
        await navigator.clipboard.writeText(walletNumber.value);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch (err) {
        console.error('Kopyalama başarısız:', err);
    }

}
// 3. Bileşen yüklendiğinde fonksiyonu tetikle
onMounted(() => {
    getDashboardData();
});
</script>
