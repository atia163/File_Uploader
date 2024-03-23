<template>
    <layout>
        <div>
            <h1>File Uploader</h1>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form @submit.prevent="submitForm">
                <dropzone id="file" ref="fileDropzone" @drop="onFileDrop" :options="dropzoneOptions"></dropzone>
                <button type="submit">Upload</button>
            </form>
        </div>
    </layout>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    props: {
        errors: Object
    },

    setup(props) {
        const { data, post, processing, progress } = useForm({
            file: null
        })

        const dropzoneOptions = {
            url: '/upload',
            paramName: 'file',
            maxFilesize: 10240, // 10 GB limit
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            },
            init: function() {
                this.on('sending', function(file, xhr, formData) {
                    formData.append('file', file);
                });
                this.on('uploadprogress', function(file, progress, bytesSent) {
                    console.log(progress);
                });
            }
        }

        const onFileDrop = (files) => {
            data.file = files[0];
        }

        const submitForm = () => {
            post('/upload', {
                onSuccess: () => {
                    // Clear file input after successful upload
                    data.file = null;
                    // Reset dropzone
                    if (this.$refs.fileDropzone) {
                        this.$refs.fileDropzone.removeAllFiles();
                    }
                }
            })
        }

        return { data, dropzoneOptions, onFileDrop, submitForm }
    }
}
</script>
