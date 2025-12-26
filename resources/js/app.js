import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue'; // [!] Vue'yu içe aktar
import TransferComponent from './components/TransferComponent.vue'; // [!] Bileşenini içe aktar
import DashboardComponent from './components/DashboardComponent.vue'; // [!] Bileşenini içe aktar
import WalletComponent from './components/WalletComponent.vue'; // [!] Bileşenini içe aktar

window.Alpine = Alpine;
Alpine.start();

// Vue Yapılandırması
// Sayfada id="app" olan bir element varsa Vue'yu başlatır
const appElement = document.getElementById('app');

if (appElement) {
    const app = createApp({});

    // Bileşeni etiket olarak kaydediyoruz
    app.component('transfer-component', TransferComponent);
    app.component('dashboard-component', DashboardComponent);
    app.component('wallet-component', WalletComponent);

    app.mount('#app');
}
