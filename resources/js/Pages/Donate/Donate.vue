<script setup>
import guestLayout from '../../Layouts/guestLayout.vue';
defineOptions({
    layout: guestLayout
})

import { usePage } from '@inertiajs/vue3';

const page = usePage();


</script>

<template>


    <div class="container my-5">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card border border-0 shadow-none">
                    <div class="card-body">
                        <h2 class="text-center fw-bold text-primary mb-4 pb-3 border-bottom border-2 border-primary ">{{ page.props.pageInfo.name }}</h2>
                        <p class="text-justify" v-html="page.props.pageInfo.des">
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-border border-primary">

                    <div class="card-header">
                        <h3 class=" fw-bold text-center text-primary">Donate Option</h3>
                    </div>

                    <div class="card-body col-md-12 d-flex flex-wrap">

                        <div class="col-md-6 px-3" v-for="donate in page.props.list" :key="donate.id">
                            <img width="100px" style="height: 60px;" class="img-fluid img-thumbnail mx-auto d-block"  :src="donate.image" alt="">

                            <h6>{{ donate.name }}</h6>
                            <p class="text-justify mt-3">{{ donate.des }}</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<style scoped></style>
