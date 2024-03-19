<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import "/resources/css/main.css";
import { sendPostRequest } from '@/Helpers/api';
import { toastError, toastSuccess } from '@/Helpers/toast';
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
            <div class="row">
                <div class="col-md-12">
                    <form @submit="createClient">
                        <h1> Sign Up </h1>

                        <fieldset>

                            <legend><span class="number">1</span> Your Basic Info</legend>

                            <div class="form-input-container">
                                <label for="name">Name:</label>
                                <input type="text" v-model="client.name" id="name">
                                <small>{{ validation_errors.name }}</small>
                            </div>

                            <div class="form-input-container">
                                <label for="email">Email:</label>
                                <input type="email" id="mail" v-model="client.email">
                                <small>{{ validation_errors.email }}</small>
                            </div>

                            <div class="form-input-container">
                                <label for="phone">Phone:</label>
                                <input type="text" id="phone" name="phone" v-model="client.phone">
                                <small>{{ validation_errors.phone }}</small>
                            </div>


                            <div class="form-input-container">
                                <label>Gender:</label>
                                <div class="radio_container">
                                    <div>
                                        <input type="radio" id="male" value="Male" v-model="client.gender"><label
                                            for="male" class="light">Male</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="female" value="Female" v-model="client.gender"><label
                                            for="female" class="light">Female</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="Other" value="Other" v-model="client.gender"><label
                                            for="Other" class="light">Others</label>
                                    </div>
                                </div>
                                <small>{{ validation_errors.gender }}</small>
                            </div>


                            <div class="form-input-container">
                                <label for="address">Address:</label>
                                <input type="text" id="address" v-model="client.address">
                                <small>{{ validation_errors.address }}</small>
                            </div>

                            <div class="form-input-container">
                                <div class="form-input-container">
                                    <label for="nationality">Nationality:</label>
                                    <select v-model="client.nationality" id="nationality">
                                        <option value="Nepal">Nepal</option>
                                        <option value="India">India</option>
                                        <option value="China">China</option>
                                    </select>
                                    <small>{{ validation_errors.nationality }}</small>
                                </div>
                            </div>

                            <div class="form-input-container">
                                <label for="dob">Date of birth:</label>
                                <input type="date" id="dob" v-model="client.dob">
                                <small>{{ validation_errors.dob }}</small>
                            </div>


                            <div class="form-input-container">
                                <label for="education_background">Education Background:</label>
                                <input type="text" id="education_background" v-model="client.education_background">
                                <small>{{ validation_errors.education_background }}</small>
                            </div>

                            <div class="form-input-container">
                                <label>Preferred mode of contact:</label>
                                <div class="radio_container">
                                    <div>
                                        <input type="radio" id="phone" value="Phone"
                                            v-model="client.preferred_contact_mode"><label for="phone"
                                            class="light">Phone</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="email" value="Email"
                                            v-model="client.preferred_contact_mode"><label for="email"
                                            class="light">Email</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="none" v-model="client.preferred_contact_mode"><label
                                            for="none" class="light">None</label>
                                    </div>
                                </div>
                                <small>{{ validation_errors.preferred_contact_mode }}</small>
                            </div>

                        </fieldset>
                        <!-- <fieldset>

                            <legend><span class="number">2</span> Your Profile</legend>

                            <label for="bio">Bio:</label>
                            <textarea id="bio" name="user_bio"></textarea>



                            <label for="job">Job Role:</label>
                            <select id="job" name="user_job">
                                <optgroup label="Web">
                                    <option value="frontend_developer">Front-End Developer</option>
                                    <option value="php_developer">PHP Developer</option>
                                    <option value="python_developer">Python Developer</option>
                                    <option value="rails_developer">Rails Developer</option>
                                    <option value="web_designer">Web Designer</option>
                                    <option value="wordpress_developer">Wordpress Developer</option>
                                </optgroup>
                                <optgroup label="Mobile">
                                    <option value="android_developer">Android Developer</option>
                                    <option value="ios_developer">IOS Developer</option>
                                    <option value="mobile_designer">Mobile Designer</option>
                                </optgroup>
                                <optgroup label="Business">
                                    <option value="business_owner">Business Owner</option>
                                    <option value="freelancer">Freelancer</option>
                                </optgroup>
                            </select>

                            <label>Interests:</label>
                            <input type="checkbox" id="development" value="interest_development"
                                name="user_interest"><label class="light" for="development">Development</label><br>
                            <input type="checkbox" id="design" value="interest_design" name="user_interest"><label
                                class="light" for="design">Design</label><br>
                            <input type="checkbox" id="business" value="interest_business" name="user_interest"><label
                                class="light" for="business">Business</label>

                        </fieldset> -->

                        <button class="button-success" type="submit">Sign Up</button>

                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
export default {
    name: "CreateClient",
    data() {
        return {
            client: {},
            validation_errors: {}
        }
    },
    methods: {
        createClient(e) {
            e.preventDefault()
            sendPostRequest("/api/v1/clients", this.client)
                .then(response => {
                    console.log(response.data)
                    this.resetClient()
                    this.validation_errors = {}
                    toastSuccess(response.data.message)
                })
                .catch(ex => {
                    if (ex.response.status == 422) {
                        this.validation_errors = ex.response.data.errors
                        return;
                    }

                    console.log(ex.response)
                    toastError("Could not save client info!")
                });
        },
        resetClient() {
            this.client = {
                name: "",
                email: "",
                phone: "",
                gender: "",
                address: "",
                nationality: "",
                dob: "",
                education_background: "",
                preferred_contact_mode: "",
            }
        }
    }
}
</script>
