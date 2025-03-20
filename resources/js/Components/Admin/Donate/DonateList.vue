<template>
    <div class="container-fluid pt-4 px-4">

        <div class="row g-4">
            <div class="col-md-12">



                <div class="card">
                    <div class="card-body">

                        <div class="d-flex flex-wrap  mb-4">
                            <div class="input-group col-md-6 col-sm-12">
                                <input class="form-control form-control-lg focus-shadow-none" type="search"
                                    placeholder="Search" aria-label="Search" v-model="searchValue">
                                <button class="btn btn-success px-4">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>

                            <div class="col-md-6 col-sm-12 mobile-margin-for-button ">
                                <button class="btn btn-success  fs-sm-4" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Create Donate</button>

                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="title">Page Title</label>
                            <input v-model="info.title" class="form-control" type="text">

                            <label for="description">Page Description</label>
                            <textarea v-model="info.description" class="form-control" name="description" id=""></textarea>

                            <button @click="infoSubmit()" class="btn btn-success mt-3">Update</button>
                        </div>


                        <!-- Modal -->
                        <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form @submit.prevent="Submit">

                                        <div class="modal-body">
                                            <div class="mt-3">
                                                <label for="status" class="form-label "> Name</label>
                                                <input v-model="form.name" type="text" class="form-control w-100"
                                                    placeholder="Donate Method Name">
                                            </div>

                                            <div class="mt-3 d-flex align-items-center">
                                                <div class="w-80">
                                                    <label for="status" class="form-label mb-0">Image</label>
                                                    <input type="file" name="" id="" @change="uploadImage($event)"
                                                        class="form-control">

                                                </div>

                                                <div class=" ml-3">
                                                    <img style="height: 50px" class="mt-3 w-100"
                                                        :src="form.prevImage ? form.prevImage : 'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg'"
                                                        alt="">

                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="status" class="form-label mb-0">Details</label>
                                                <textarea name="status" class="form-control" id="" rows="3"
                                                    v-model="form.des" placeholder="Write Details..."></textarea>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-secondary"
                                                :disabled="form.processing">Save
                                                changes</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div>


                            <EasyDataTable buttons-pagination alternating :headers="header" :items="item"
                                theme-color="#009A31" :rows-per-page="5" table-class-name="customize-table"
                                :search-field="searchField" :search-value="searchValue" show-index>

                                <template #item-image="{ image }">
                                    <img style="width: 50px; height: 50px;" :src="image" alt="">
                                </template>
                                <template #item-des="{ des }">
                                    <p class="text-truncate" style="max-width: 200px;">{{ des }}</p>
                                </template>

                                <template #item-number="{ id, name, image, des }">
                                    <div class="d-flex align-items-center my-2">
                                        <Link class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal"
                                            data-bs-target="#editModal" @click="categoryEditClick(id, name, image, des)"
                                            href="#"><i class="fa-regular fa-pen-to-square"></i></Link>
                                        <Link class="btn btn-sm btn-danger" href="#" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" @click="categoryDeleteClick(id, image)"><i
                                            class="fa-regular fa-trash-can"></i></Link>
                                    </div>
                                </template>


                            </EasyDataTable>


                        </div>


                        <!-- edit Modal -->
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div class="mt-3">
                                            <label for="status" class="form-label "> Name</label>
                                            <input v-model="categoryEdit.name" type="text" class="form-control w-100"
                                                placeholder="Donate Method Name">
                                        </div>

                                        <div class="mt-3 d-flex align-items-center">
                                            <div class="w-80">
                                                <label for="status" class="form-label mb-0">Image</label>
                                                <input type="file" name="" id="" @change="uploadImage2($event)"
                                                    class="form-control">

                                            </div>

                                            <div class=" ml-3">
                                                <img style="height: 50px" class="mt-3 w-100"
                                                    :src="categoryEdit.prevImage ? categoryEdit.prevImage : 'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg'"
                                                    alt="">

                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label for="status" class="form-label mb-0">Details</label>
                                            <textarea name="status" class="form-control" id="" rows="3"
                                                v-model="categoryEdit.des" placeholder="Write Details..."></textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                            @click="form.reset()">Close</button>
                                        <button type="button" @click="categoryUpdateSubmit" class="btn btn-secondary"
                                            :disabled="form.processing">Save
                                            changes</button>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <!-- delete Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure?</h1>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">No</button>
                                        <button type="button" class="btn btn-secondary"
                                            :disabled="categoryDelete.processing" @click="deleteConfirm()">Yes</button>
                                    </div>


                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>


    </div>



</template>

<script setup>

import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';


// data table info

const header = [
    { text: 'Name', value: 'name' },
    { text: 'Image', value: 'image' },
    { text: 'Details', value: 'des' },
    { text: 'Action', value: 'number' },
]

const page = usePage();

const searchField = ['name'];
const searchValue = ref('');
const item = ref(page.props.list);




const form = useForm({
    name: '',
    des: '',
    img: '',
    prevImage: ''

})

const uploadImage = (event) => {
    form.img = event.target.files[0];
    form.prevImage = URL.createObjectURL(event.target.files[0]);
}
// for create category
const Submit = () => {

    if (form.name == '') {
        new Notify({
            status: 'error',
            title: 'Donate name is required',
            autotimeout: 2000,
        })

    } else if (form.des == '') {
        new Notify({
            status: 'error',
            title: 'Description is required',
            autotimeout: 2000,
        })
    } else if (form.img == '') {
        new Notify({
            status: 'error',
            title: 'Image is required',
            autotimeout: 2000,
        })
    }
    else {

        form.post('/user-donate-create', {
            onSuccess: () => {
                Modal.getInstance('#exampleModal').hide();
                if (page.props.flash.success) {
                    new Notify({
                        status: 'success',
                        title: page.props.flash.success.message,
                        autotimeout: 2000,
                    })

                    form.reset();
                    router.get("/user-donate");

                } else if (page.props.flash.error) {
                    new Notify({
                        status: 'error',
                        title: page.props.flash.error.message,
                        autotimeout: 2000,
                    })
                }
            },
            onError: () => {
                new Notify({
                    status: 'error',
                    title: 'Create failed',
                    autotimeout: 2000,
                })
            }
        },
        );

    }

}

//  for edit category

const categoryEdit = useForm({
    id: '',
    name: '',
    des: '',
    img: '',
    prevImage: '',
    oldImage: ''

})


const categoryEditClick = (id, name, image, des) => {
    categoryEdit.id = id;
    categoryEdit.name = name;
    categoryEdit.des = des;
    categoryEdit.prevImage = image;
    categoryEdit.oldImage = image;
}
const uploadImage2 = (event) => {
    categoryEdit.img = event.target.files[0];
    categoryEdit.prevImage = URL.createObjectURL(event.target.files[0]);
}
const categoryUpdateSubmit = () => {

    if (categoryEdit.name == '') {
        new Notify({
            status: 'error',
            title: 'Donate name is required',
            autotimeout: 2000,
        })

    } else if (categoryEdit.des == '') {
        new Notify({
            status: 'error',
            title: 'Description is required',
            autotimeout: 2000,
        })
    }
    else {

        categoryEdit.post('/user-donate-update', {
            onSuccess: () => {
                Modal.getInstance('#editModal').hide();
                if (page.props.flash.success) {
                    new Notify({
                        status: 'success',
                        title: page.props.flash.success.message,
                        autotimeout: 2000,
                    })

                    form.reset();
                    router.get("/user-donate");

                } else if (page.props.flash.error) {
                    new Notify({
                        status: 'error',
                        title: page.props.flash.error.message,
                        autotimeout: 2000,
                    })
                }
            },
            onError: () => {
                new Notify({
                    status: 'error',
                    title: 'Create failed',
                    autotimeout: 2000,
                })
            }
        },


        );

    }


}



// for delete category

const categoryDelete = useForm({
    id: '',
    imagePath: '',
})
const categoryDeleteClick = (id, image) => {
    categoryDelete.id = id;
    categoryDelete.imagePath = image;

}

const deleteConfirm = () => {

    categoryDelete.delete('/user-donate-delete', {
        onSuccess: () => {
            Modal.getInstance('#deleteModal').hide();
            if (page.props.flash.success) {
                new Notify({
                    status: 'success',
                    title: page.props.flash.success.message,
                    autotimeout: 2000,
                })
                router.get("/user-donate");
            } else if (page.props.flash.error) {
                new Notify({
                    status: 'error',
                    title: page.props.flash.error.message,
                    autotimeout: 2000,
                })
            }
        },

        onError: () => {
            new Notify({
                status: 'error',
                title: 'Delete failed',
                autotimeout: 2000,
            })
        }
    },

    );

}


// page title and description

const info = useForm({
    title: page.props.pageInfo.name,
    description: page.props.pageInfo.des,
})

const infoSubmit = () => {
    if (info.title == '') {
        new Notify({
            status: 'error',
            title: 'Title is required',
            autotimeout: 2000,
        })
    } else if (info.description == '') {
        new Notify({
            status: 'error',
            title: 'Description is required',
            autotimeout: 2000,
        })
    } else {
        info.post('/user-donate-update-info', {
            onSuccess: () => {
                if (page.props.flash.success) {
                    new Notify({
                        status: 'success',
                        title: page.props.flash.success.message,
                        autotimeout: 2000,
                    })
                } else if (page.props.flash.error) {
                    new Notify({
                        status: 'error',
                        title: page.props.flash.error.message,
                        autotimeout: 2000,
                    })
                }

                router.get("/user-donate");
            },
            onError: () => {
                new Notify({
                    status: 'error',
                    title: 'Update failed',
                    autotimeout: 2000,
                })
            }
        },

        );
    }
}


</script>

<style scoped>
input:focus,
textarea:focus,
select:focus,
button:focus {
    outline: none !important;
    box-shadow: none !important;
}

@media (max-width: 767px) {
    .mobile-margin-for-button {
        margin-top: 20px !important;
        text-align: center !important;
    }
}

.mobile-margin-for-button {
    text-align: right !important;
}
</style>
