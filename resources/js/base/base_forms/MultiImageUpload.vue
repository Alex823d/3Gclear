<template>
    <div>
        <div class="block">
            <el-row v-loading="loading"
                    element-loading-text="Loading..."
                    element-loading-spinner="el-icon-loading"
                    element-loading-background="rgba(0, 0, 0, 0)"
                    class="form-horizontal form-bordered">
                <el-upload
                    action="#"
                    list-type="picture-card"
                    :file-list="fileList"
                    :on-change="handleChange"
                    multiple
                    :auto-upload="false">
                    <i slot="default" class="el-icon-plus" :title="lang.multi_upload"></i>
                    <div slot="file" slot-scope="{file}">
                        <img
                            class="el-upload-list__item-thumbnail"
                            :src="file.url" alt=""
                        >
                        <span class="el-upload-list__item-actions">
                        <span
                            class="el-upload-list__item-preview"
                            @click="handlePictureCardPreview(file)"
                        >
                      <i class="el-icon-zoom-in"></i>
                    </span>
                  </span>
                    </div>
                </el-upload>
                <el-dialog :visible.sync="dialogVisible">
                    <img width="100%" :src="dialogImageUrl" alt="">
                </el-dialog>

                <draggable tag="div" :list="form.default_value" handle=".handle">

                    <div class="padding-t col-md-4 col-sm-2" v-for="(element, idx) in form.default_value">

                        <div class="block">

                            <el-button
                                :title="lang.image_drag"
                                size="small"
                                type="info is-plain"
                                icon="el-icon-sort"
                                class="handle">
                            </el-button>

                            <el-button
                                v-if="form.front_options && form.front_options.has_captions"
                                @click="showEditImage(idx)"
                                :title="lang.image_edit"
                                size="small"
                                type="primary"
                                icon="el-icon-edit">
                            </el-button>

                            <el-button
                                @click="removeImage(idx)"
                                :title="lang.image_delete"
                                size="small"
                                type="danger"
                                icon="el-icon-delete">
                            </el-button>

                            <el-row :gutter="20">
                                <el-col :sm="24" :span="24" class="padding-tb">{{ default_locale }}
                                    <div style="height: 200px; overflow: hidden;">
                                        <img :src="element.full_src" style="width: 100%;">
                                    </div>
                                    <span>{{ element.fields ? element.fields[activeTabName].title  : ''}}</span>
                                </el-col>
                            </el-row>

                        </div>

                    </div>
                    <div style="clear: left;"></div>
                </draggable>

                <AddImageComponent
                    v-if="showModal"
                    :updateImageData="updateImageData"
                    :lang="lang"
                    :upload_image_route="uploadImageRoute"
                    :locales="locales"
                    :edit_data="edit_data"
                    :attribute="form"
                    :default_locale="default_locale">
                </AddImageComponent>
            </el-row>
        </div>
    </div>
</template>

<style>
    .block .el-upload__input{
        display: none;
    }
</style>

<script>
    import {responseParse} from '../mixins/responseParse'
    import {getData} from '../mixins/getData'
    import draggable from "vuedraggable";
    import AddImageComponent from "./partials/AddImageComponent";
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import MyUploadAdapter from '../mixins/EditorCustomUpload';

    export default {
        components: {AddImageComponent, draggable},
        props: [
            'updateData',
            'lang',
            'uploadImageRoute',
            'locales',
            'default_locale',
            'attribute',
            'attributeKey'
        ],
        created() {
            this.form = this.attribute;
            if (!this.form.default_value) {
                this.form.default_value = []
            }
        },
        data() {
            return {
                activeTabName: 'ka',
                dialogVisible: false,
                loading: false,
                form: {},
                showModal: false,
                dialogImageUrl: '',
                disabled: false,
                fileList: [],
                edit_data: {}
            }
        },
        updated() {
            this.updateData(this.module_name, this.form);
        },

        methods: {
            handleRemove(file) {
                this.fileList = this.fileList.filter(function(item){
                    return item.uid != file.uid;
                });
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },

            async handleChange(file, fileList) {
                let fileItem = file.raw;

                var data = new FormData();
                data.append('file', fileItem);
                data.append('type', 'gallery_image');

                await getData({
                    method: 'POST',
                    url: this.uploadImageRoute,
                    data: data
                }).then(response => {
                    // Parse response notification.
                    responseParse(response);
                    if (response.status == 200) {
                        // Response data.
                        let data = response.data;
                        this.updateImageData(data.item);
                    }
                    this.loading = false
                });

                this.fileList = [];
            },

            /**
             * Upload custom image in editor.
             */
            meyCustomUploadAdapterPlugin(editor) {

                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    // Configure the URL to the upload script in your back-end here!
                    return new MyUploadAdapter(loader);
                };

            },

            showAdd() {
                this.edit_data = undefined;
                this.forceRerender(true);
            },

            showEditImage(index) {
                this.edit_data = '';
                this.form.default_value[index].index = index;
                this.edit_data = Object.assign({}, JSON.parse(JSON.stringify(this.form.default_value[index])));
                this.forceRerender(true);
            },

            removeImage(index) {
                this.$confirm(this.lang.remove_image_confirm, {
                    confirmButtonText: this.lang.remove_image_confirm_yes,
                    cancelButtonText: this.lang.remove_image_confirm_no,
                    type: 'warning'
                })
                    .then(async () => {
                        this.form.default_value = this.form.default_value.filter((item, i) => {
                            return i != index;
                        });
                    });
            },

            /**
             *
             * @param data
             */
            updateImageData(data = undefined, index = '') {
                if (data && index !== '') {
                    let oldValues = this.form.default_value;
                    this.form.default_value = [];
                    oldValues[index] = data;
                    oldValues[index] = {...oldValues[index], ...{full_src: data.full_src}}
                    this.form.default_value = oldValues;
                } else if (data) {
                    this.form.default_value.push(data);
                } else {
                    let oldData = this.form;
                    this.form = {};
                    this.form = oldData;
                }
            },

            forceRerender(showComponent = false) {
                // Remove my-component from the DOM
                this.showModal = !showComponent;
                this.$nextTick(() => {
                    // Add the component back in
                    this.showModal = showComponent;
                });
            },

            /**
             *
             * @param items
             * @param locale
             */
            getTranslationItem(items, locale) {

                let searchItem = {};

                items.forEach((item) => {
                    if (item.locale == locale) {
                        searchItem = item;
                    }
                });

                return searchItem;
            }

        }

    }

</script>
