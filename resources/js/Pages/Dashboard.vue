<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

// Initialize uploadedFile as null
const uploadedFile = ref(null);

const fileUpload = (event) => {
    const selectedFile = event.target.files[0];
    const fileReader = new FileReader();

    fileReader.onload = (e) => {
        const contents = e.target.result;
        try {
            uploadedFile.value = JSON.parse(contents);
        } catch (error) {
            console.error("Error parsing JSON:", error);
            // Handle the error, such as displaying a message to the user
        }
    };

    fileReader.onerror = (e) => {
        console.error("Error reading file:", e.target.error);
        // Handle the error, such as displaying a message to the user
    };
    fileReader.readAsText(selectedFile);
}

const submit = async (event) => {
    try {
        // Access the form element using event.currentTarget
        const formElement = event.currentTarget;
        // Access the selected file directly from the event
        const selectedFile = formElement.querySelector('input[type="file"]').files[0];

        const formData = new FormData();
        formData.append('jsonFile', selectedFile);

        const response = await axios.post('/file/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log('File uploaded successfully:', response.data);
        // You can handle the response here as needed
    } catch (error) {
        console.error('Error uploading file:', error);
        // Handle the error, such as displaying a message to the user
    }
}
</script>
<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                    <a :href="route('file.index')">Upload File</a>
                    <form @submit.prevent="submit">
                        <div class="m-4">
                            <h2 class="block text-gray-700">Upload Json File</h2>
                            <input type="file" name="jsonFile" class="form-control-file" @change="fileUpload($event)">
                        </div>
                        <button class="m-1 ml-3 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Submit
                        </button>
                    </form>
                    <div v-if="uploadedFile">
                        <h1 id="heading">Preview Uploaded Json File</h1>
                        <div class="m-4 uploaded-file-container">
                            <pre class="uploaded-file-content">{{ uploadedFile }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<style>
.uploaded-file-container {
    max-height: 200px;
    overflow-y: auto;
}

.uploaded-file-content {
    margin: 0;
    padding: 8px;
}

#heading {
    position: absolute;
}
</style>
