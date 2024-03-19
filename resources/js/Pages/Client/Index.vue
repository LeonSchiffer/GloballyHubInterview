<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import "/resources/css/main.css";
import { sendGet, sendPostRequest } from '@/Helpers/api';
import { toastError, toastSuccess } from '@/Helpers/toast';
import VPagination from "@hennge/vue3-pagination";
</script>

<template>

    <Head title="Dashboard" />
    <GuestLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <!-- <link rel="stylesheet" href="https://codepen.io/gymratpacks/pen/VKzBEp#0"> -->
            <!-- <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'> -->
            <!-- <link rel="stylesheet" href="css/main.css"> -->
            <div class="per_page_container">
                <label for="">Per Page</label>
                <input type="number" min="1" v-model="per_page" @change="perPageChange">
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Nationality</th>
                        <th>DOB</th>
                        <th>Education Background</th>
                        <th>Preferred Contact Mode</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="client in clients.data">
                        <td>{{ client.name }}</td>
                        <td>{{ client.email }}</td>
                        <td>{{ client.phone }}</td>
                        <td>{{ client.gender }}</td>
                        <td>{{ client.address }}</td>
                        <td>{{ client.nationality }}</td>
                        <td>{{ client.dob }}</td>
                        <td>{{ client.education_background }}</td>
                        <td>{{ client.preferred_contact_mode }}</td>
                        <!-- <td>{{ client.created_at}}</td> -->
                    </tr>
                </tbody>
            </table>
            <VPagination
            v-model="clients.current_page"
            :pages="clients.last_page"
            @update:modelValue="updateHandler"
            active-color="#DCEDFF"
            />
        </div>
    </GuestLayout>
</template>

<script>
export default {
    data() {
        return {
            clients: [],
            per_page: 15
        }
    },
    mounted() {
        this.getClientList(this.per_page)
    },
    methods: {
        getClientList(per_page, page = 1)
        {
            sendGet("clients", {
                page,
                limit: per_page
            })
            .then(response => {
                console.log(response)
                this.clients = response.data
            })
            .catch(ex => {
                console.log(ex.reponse)
                toastError("Could not fetch client list")
            })
        },
        updateHandler(page) {
            this.getClientList(per_page, page)
        },
        perPageChange(e) {
            var value = e.target.value
            value = value <=0 ? 15 : value
            this.per_page = value
            this.getClientList(this.per_page, this.clients.current_page)
        }
    }
}
</script>
