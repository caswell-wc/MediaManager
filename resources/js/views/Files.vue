<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-2">
                    <div class="card-header">Your Files</div>

                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex" v-for="(file, index) in files">
                                <a href="#" @click.prevent="showFile(file)" class="flex-grow-1 mb-0">{{file.name}}</a>
                                <a href="#" class="btn btn-default text-danger" @click.prevent="deleteFile(file, index)">X</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Upload File</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="name">File Name</label>
                                <input type="text" class="form-control" name="name" v-model="newName">
                            </div>
                            <div class="form-group">
                                <label for="file">Select your file</label>
                                <input type="file" class="form-control" style="height: auto" ref="file" @change="handleFileChange()">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" @click.prevent="uploadFile()">
                                    <span v-if="uploading">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span>Uploading...</span>
                                    </span>
                                    <span v-else>Upload</span>
                                </button>
                            </div>
                            <div class="alert alert-danger" v-if="errors.length">
                                <ul>
                                    <li v-for="error in errors">{{error}}</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <modal name="show-file" height="auto" width="50%">
            <img :src="path" :alt="currentFileName" v-if="showImage" width="100%">
            <video :src="path" v-else width="100%" controls></video>
        </modal>
    </div>
</template>

<script>
    import FilesService from "../services/FilesService";

    export default {
        data() {
            return {
                files: [],
                newName: '',
                newFile: {},
                errors: [],
                showImage: true,
                path: '',
                currentFileName: '',
                uploading: false,
                filesService: {}
            }
        },
        created() {
            this.filesService = new FilesService();
            this.loadFiles();
        },
        methods: {
            async loadFiles() {
                try {
                    this.files = await this.filesService.getFiles();
                } catch(error) {
                    this.errors = ['Error loading files'];
                }
            },
            handleFileChange() {
                this.newFile = this.$refs.file.files[0];
            },
            async uploadFile() {
                this.uploading = true;
                this.errors = [];

                try {
                    const file = await this.filesService.uploadFile(this.newName, this.newFile);
                    this.files.push(file);
                    this.uploading = false;
                    this.newName = '';
                } catch (error) {
                    const errors = error.errors;
                    Object.keys(errors).forEach(key => {
                        this.errors.concat(errors[key]);
                    })
                    this.errors = error.errors.file;
                }
            },
            showFile(file) {
                this.path = '/files/' + file.id;
                this.showImage = true;
                if (file.type == 'video') {
                    this.showImage = false;
                }
                this.$modal.show('show-file');
            },
            async deleteFile(file, index) {
                if (confirm("Delete your " + file.name + " file?")) {
                    try {
                        this.filesService.deleteFile(file.id);
                        this.files.splice(index, 1);
                    } catch (error) {
                        this.errors = ['Error deleting the file'];
                    }
                }
            }
        }
    }
</script>
