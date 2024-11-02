<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from 'primevue/dialog';
import InputText from "primevue/inputtext";
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const confirm = useConfirm();

const selectedSeller = ref({ name: '', email: '' });
const sellers = ref([]);
const visible = ref(false);
const showData = ref(false);
const seller = ref([]);

function getSellers() {
    axios.get('/api/v1/sellers')
        .then(response => {
            sellers.value = response.data.data.sellers;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function storeSeller() {
    axios.post('/api/v1/sellers', selectedSeller.value)
        .then(response => {
            sellers.value.push(response.data.data.seller);
            visible.value = false;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function showSeller(id) {
    axios.get(`/api/v1/sellers/${id}`)
        .then(response => {
            seller.value = response.data.data.seller;
            showData.value = true;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function editSeller(seller) {
    selectedSeller.value = { ...seller };
    visible.value = true;
}

function updateSeller() {
    axios.put(`/api/v1/sellers/${selectedSeller.value.id}`, selectedSeller.value)
        .then(response => {
            const index = sellers.value.findIndex(v => v.id === selectedSeller.value.id);
            if (index !== -1) sellers.value[index] = response.data.data.seller;
            visible.value = false;
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function submitForm() {
    if (selectedSeller.value.id) {
        updateSeller();
    } else {
        storeSeller();
    }
}

function deleteSeller(id) {
    confirm.require({
        message: 'Do you want to remove the seller?',
        header: 'Confirm Removal',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
            axios.delete(`/api/v1/sellers/${id}`)
                .then(response => {
                    sellers.value = sellers.value.filter(seller => seller.id !== id);
                })
                .catch(error => {
                    alert(error.response.data.message);
                });
        }
    });
}

function sendDailyReport(id) {
    axios.get(`/api/v1/sellers/${id}/send-mail`,)
        .then(response => {
            alert('Email sent');
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

onMounted(() => {
    getSellers();
});
</script>

<template>

    <Head title="Sellers" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Sellers
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-end mb-4">
                            <Button class="p-button ms-4" @click="visible = true">
                                <i class="pi pi-plus mr-2"></i>New Seller
                            </Button>
                        </div>
                        <p v-if="sellers.length === 0">No sellers found!</p>
                        <DataTable v-else :value="sellers" tableStyle="min-width: 50rem">
                            <Column field="name" header="Name" />
                            <Column field="email" header="Email" />
                            <Column header="Actions" class="flex justify-end">
                                <template #body="slotProps">
                                    <Button icon="pi pi-eye" style="color: grey" text rounded
                                        @click="showSeller(slotProps.data.id)" />
                                    <Button icon="pi pi-inbox" style="color: green" text rounded
                                        @click="sendDailyReport(slotProps.data.id)" />
                                    <Button icon="pi pi-pencil" style="color: grey" text rounded
                                        @click="editSeller(slotProps.data)" />
                                    <Button icon="pi pi-trash" severity="danger" text rounded
                                        @click="deleteSeller(slotProps.data.id)" />
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="visible" modal header="Seller Register" :style="{ width: '40rem' }">
            <div class="flex items-center gap-4 mb-4">
                <label for="name" class="font-semibold w-24">Name</label>
                <InputText v-model="selectedSeller.name" id="name" class="flex-auto" autocomplete="off" />
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label for="email" class="font-semibold w-24">Email</label>
                <InputText v-model="selectedSeller.email" id="email" class="flex-auto" autocomplete="off" />
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
                <Button type="button" label="Save" @click="submitForm()"></Button>
            </div>
        </Dialog>

        <Dialog v-model:visible="showData" modal header="Seller info" :style="{ width: '40rem' }">
            <div class="flex items-center gap-4 mb-4">
                <p class="font-semibold">Name: {{ seller.name }}</p>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Email: {{ seller.email }}</label>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Created at: {{ seller.created_at }}</label>
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label class="font-semibold">Updated at: {{ seller.updated_at }}</label>
            </div>
        </Dialog>

        <ConfirmDialog></ConfirmDialog>

    </AuthenticatedLayout>
</template>
