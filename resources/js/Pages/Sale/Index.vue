<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from 'primevue/dialog';
import InputNumber from 'primevue/inputnumber';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import Dropdown from "primevue/dropdown";
import DatePicker from 'primevue/datepicker';

const confirm = useConfirm();

const selectedSale = ref({ seller_id: '', value: '', date: '' });
const sales = ref([]);
const sale = ref([]);
const sellers = ref([]);
const visible = ref(false);
const showData = ref(false);

function getSellers() {
    axios.get('/api/v1/sellers')
        .then(response => {
            sellers.value = response.data.data.sellers;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function getSales() {
    axios.get('/api/v1/sales')
        .then(response => {
            sales.value = response.data.data.sales;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function getSalesBySeller(seller) {
    axios.get(`/api/v1/sales/by/${seller.value}`)
        .then(response => {
            sales.value = response.data.data.sales;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function storeSale() {
    axios.post('/api/v1/sales', selectedSale.value)
        .then(response => {
            sales.value.push(response.data.data.sale);
            visible.value = false;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function showSale(id) {
    axios.get(`/api/v1/sales/${id}`)
        .then(response => {
            sale.value = response.data.data.sale;
            showData.value = true;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function editSale(sale) {
    selectedSale.value = { ...sale };
    visible.value = true;
}

function updateSale() {
    axios.put(`/api/v1/sales/${selectedSale.value.id}`, selectedSale.value)
        .then(response => {
            const index = sales.value.findIndex(v => v.id === selectedSale.value.id);
            if (index !== -1) sales.value[index] = response.data.data.sale;
            visible.value = false;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function submitForm() {
    if (selectedSale.value.id) {
        updateSale();
    } else {
        storeSale();
    }
}

function deleteSale(id) {
    confirm.require({
        message: 'Do you want to remove the sale?',
        header: 'Confirm Removal',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
            axios.delete(`/api/v1/sales/${id}`)
                .then(response => {
                    sales.value = sales.value.filter(sale => sale.id !== id);
                })
                .catch(error => {
                    alert(error.response.data.message);
                });
        }
    });
}

// Clean the form inputs
watch(visible, (newValue) => {
    if (!newValue) {
        selectedSale.value = { seller_id: '', value: '', date: '' };
    }
});

onMounted(() => {
    getSales();
    getSellers();
});
</script>

<template>

    <Head title="Sales" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Sales
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-end mb-4">
                            <Dropdown optionLabel="name" placeholder="Filter sales by Seller" optionValue="id"
                                :options="sellers" @change="getSalesBySeller" />

                            <Button class="p-button ms-4" @click="visible = true">
                                <i class="pi pi-plus mr-2"></i>New Sale
                            </Button>
                        </div>
                        <p v-if="sales.length === 0">No sales found!</p>
                        <DataTable v-else :value="sales" tableStyle="min-width: 50rem">
                            <Column field="seller_id" header="Seller">
                                <template #body="slotProps">
                                    {{ slotProps.data.seller.name }}
                                </template>
                            </Column>
                            <Column field="value" header="Value">
                                <template #body="slotProps">
                                    R${{ slotProps.data.value.toFixed(2) }}
                                </template>
                            </Column>
                            <Column field="commission" header="Commission">
                                <template #body="slotProps">
                                    R${{ slotProps.data.commission.toFixed(2) }}
                                </template>
                            </Column>
                            <Column field="date" header="Date" />
                            <Column header="Actions" class="flex justify-end">
                                <template #body="slotProps">
                                    <Button icon="pi pi-eye" style="color: grey" text rounded
                                        @click="showSale(slotProps.data.id)" />
                                    <Button icon="pi pi-pencil" style="color: grey" text rounded
                                        @click="editSale(slotProps.data)" />
                                    <Button icon="pi pi-trash" severity="danger" text rounded
                                        @click="deleteSale(slotProps.data.id)" />
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="visible" modal header="Sale Register" :style="{ width: '40rem' }">
            <div class="flex items-center gap-4 mb-4">
                <label for="seller_id" class="font-semibold w-24">Seller</label>
                <Dropdown optionLabel="name" placeholder="Seller" optionValue="id" :options="sellers"
                    v-model="selectedSale.seller_id" />
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label for="value" class="font-semibold w-24">Value (cents)</label>
                <InputNumber v-model="selectedSale.value" id="value" class="flex-auto" mode="decimal" :minFractionDigits="2" />
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label for="date" class="font-semibold w-24">Date</label>
                <DatePicker v-model="selectedSale.date" id="date" class="flex-auto" dateFormat="yy-mm-dd" />
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
                <Button type="button" label="Save" @click="submitForm()"></Button>
            </div>
        </Dialog>

        <Dialog v-model:visible="showData" modal header="Sale info" :style="{ width: '40rem' }">
            <div class="flex items-center gap-4 mb-4">
                <p class="font-semibold">Seller: {{ sale.seller.name }}</p>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Value: R${{ sale.value }}</label>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Commission: R${{ sale.commission }}</label>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Date: {{ sale.date }}</label>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Created at: {{ sale.created_at }}</label>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Updated at: {{ sale.updated_at }}</label>
            </div>
        </Dialog>

        <ConfirmDialog></ConfirmDialog>

    </AuthenticatedLayout>
</template>
