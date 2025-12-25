<template>
    <div style="max-width: 400px; margin: 20px auto; padding: 20px; border: 1px solid #ddd;">
        <h3>Para Gönder</h3>

        <form @submit.prevent="gonder">
            <div>
                <label>Alıcı No:</label>
                <input v-model="iban" type="text" placeholder="TR..." required style="width: 100%; margin-bottom: 10px;">
            </div>

            <div>
                <label>Miktar:</label>
                <input v-model="miktar" type="number" placeholder="0.00" required style="width: 100%; margin-bottom: 10px;">
            </div>

            <button type="submit" style="width: 100%; padding: 10px; background: blue; color: white;">
                Parayı Gönder
            </button>
        </form>

        <p v-if="mesaj" style="margin-top: 15px; color: green;">{{ mesaj }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// Form verileri için basit değişkenler
const iban = ref('');
const miktar = ref(0);
const mesaj = ref('');

const gonder = async () => {
    try {
        // Laravel web.php rotasına istek atar
        const response = await axios.post('/sendMoney', {
            wallet_number: iban.value,
            amount: miktar.value
        });

        mesaj.value = response.data.message; // "Transfer başarılı" mesajı
        iban.value = ''; // Inputu temizle
        miktar.value = 0; // Inputu temizle
    } catch (error) {
        mesaj.value = "Hata: " + error.response.data.message;
    }
};
</script>
