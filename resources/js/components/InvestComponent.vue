<script setup>
</script>
<template>
    <div class="bg-light min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <p class="text-muted">hisse seçin</p>
                        <select v-model="selected">
                            <!-- inline object literal -->
                            <option :value="{ number: 123 }">123</option>
                        </select>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
