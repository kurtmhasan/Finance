import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue'; // [!] Vue'yu içe aktar
import TransferComponent from './components/TransferComponent.vue'; // [!] Bileşenini içe aktar
import InvestmentComponent from './components/InvestmentComponent.vue'; // [!] Bileşenini içe aktar
import DashboardComponent from './components/DashboardComponent.vue'; // [!] Bileşenini içe aktar
import InvestComponent from './components/InvestComponent.vue'; // [!] Bileşenini içe aktar
window.Alpine = Alpine;
Alpine.start();

// Vue Yapılandırması
// Sayfada id="app" olan bir element varsa Vue'yu başlatır
const appElement = document.getElementById('app');

if (appElement) {
    const app = createApp({});

    // Bileşeni etiket olarak kaydediyoruz
    app.component('transfer-component', TransferComponent);
    app.component('investment-component', InvestmentComponent);
    app.component('dashboard-component', DashboardComponent);
    app.component('invest-component', InvestComponent);

    app.mount('#app');
}
