<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import {
    Chart,
    ArcElement,
    Tooltip,
    Legend
} from 'chart.js'

Chart.register(ArcElement, Tooltip, Legend)

const canvasRef = ref(null)
let chart = null

onMounted(() => {
    chart = new Chart(canvasRef.value, {
        type: 'pie',
        data: {
            labels: ['Nakit', 'Hisse', 'Kripto'],
            datasets: [{
                data: [30000, 50000, 20000]
            }]
        }
    })
})

onBeforeUnmount(() => {
    chart?.destroy()
})
</script>
<template>
    <div class="bg-light min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h1 class="fw-bold text-primary">45.250,00 ₺</h1>
                        <p class="text-muted">wfsdfdfsdf</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
