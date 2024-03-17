<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import "/resources/css/main.css"
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
                    <form action="index.html" method="post">
                        <h1> Sign Up </h1>

                        <fieldset>

                            <legend><span class="number">1</span> Your Basic Info</legend>

                            <label for="name">Name:</label>
                            <input type="text" id="name" name="user_name">

                            <label for="email">Email:</label>
                            <input type="email" id="mail" name="user_email">

                            <label for="phone">Phone:</label>
                            <input type="text" id="phone" name="phone">

                            <label>Gender:</label>
                            <div class="radio_container">
                                <div>
                                    <input type="radio" id="male" value="male" name="user_age"><label
                                        for="male" class="light">Male</label>
                                </div>
                                <div>
                                    <input type="radio" id="Female" value="Female" name="user_age"><label
                                        for="Female" class="light">Female</label>
                                </div>
                                <div>
                                    <input type="radio" id="Other" value="Other" name="user_age"><label
                                        for="Other" class="light">Other</label>
                                </div>
                            </div>

                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address">

                            <label for="nationality">Nationality:</label>
                            <select name="nationality" id="nationality">
                                <option value="Nepal">Nepal</option>
                                <option value="India">India</option>
                                <option value="China">China</option>
                            </select>

                            <label for="dob">Date of birth:</label>
                            <input type="date" id="dob" name="dob">

                            <label for="education_background">Education Background:</label>
                            <input type="text" id="education_background" name="education_background">

                            <label>Preferred mode of contact:</label>
                            <div class="radio_container">
                                <div>
                                    <input type="radio" id="phone" value="phone" name="preferred_contact_mode"><label
                                        for="phone" class="light">Phone</label>
                                </div>
                                <div>
                                    <input type="radio" id="email" value="email" name="preferred_contact_mode"><label
                                        for="email" class="light">Email</label>
                                </div>
                                <div>
                                    <input type="radio" id="none" value="none" name="preferred_contact_mode"><label
                                        for="none" class="light">None</label>
                                </div>
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

                        <button type="submit">Sign Up</button>

                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
