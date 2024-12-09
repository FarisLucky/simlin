<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        hide-footer
        ref="modalRef"
    >
        <div class="mb-1">
            <div class="mb-1">
                <form @submit.prevent="onSubmit">
                    <BRow class="g-2 align-items-end">
                        <BCol sm="6" md="4">
                            <div class="mb-1">
                                <label>Nama</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="modalForm.nama"
                                />
                                <span v-if="modalErr?.nama" class="text-danger">
                                    {{ modalErr.nama[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol sm="6" md="4">
                            <div class="mb-1">
                                <label class="mb-1">Kategori</label>
                                <v-select
                                    v-model="modalForm.id_kategori"
                                    :options="mKategoriList"
                                    label="nama"
                                    :reduce="(g) => g.id"
                                    placeholder="Pilih Kategori"
                                >
                                </v-select>
                                <span
                                    v-if="modalErr?.id_kategori"
                                    class="text-danger"
                                >
                                    {{ modalErr.id_kategori[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol>
                            <div class="mb-1">
                                <BButton
                                    type="submit"
                                    variant="primary"
                                    :disabled="modalSubmit"
                                >
                                    {{
                                        modalSubmit ? "Sedang Proses" : "Simpan"
                                    }}
                                </BButton>
                            </div>
                        </BCol>
                    </BRow>
                </form>
            </div>
            <hr v-if="modalEditable" class="m-1" />
            <div v-if="modalEditable" class="m-1">
                <form @submit.prevent="onSubmitDetail">
                    <BRow class="g-2 align-items-end">
                        <BCol sm="12">
                            <h5 class="m-0">Detail Bundle</h5>
                        </BCol>
                        <BCol sm="6" md="4">
                            <div class="mb-1">
                                <label>Alat</label>
                                <v-select
                                    v-model="modalFormDetail.kode_alat"
                                    :options="mAlatList"
                                    label="nama"
                                    :reduce="(g) => g.kode"
                                    placeholder="Pilih Alat"
                                >
                                </v-select>
                                <span
                                    v-if="modalErr?.kode_alat"
                                    class="text-danger"
                                >
                                    {{ modalErr.kode_alat[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol v-if="modalFormDetail.kode_alat" sm="6" md="3">
                            <div class="mb-1">
                                <label class="mb-1">Jumlah</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model="modalFormDetail.jml"
                                />
                                <span v-if="modalErr?.jml" class="text-danger">
                                    {{ modalErr.jml[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol>
                            <div class="mb-1">
                                <BButton
                                    type="submit"
                                    variant="primary"
                                    :disabled="modalSubmit"
                                >
                                    {{
                                        modalSubmit ? "Sedang Proses" : "Simpan"
                                    }}
                                </BButton>
                            </div>
                        </BCol>
                    </BRow>
                </form>
            </div>
            <div v-if="modalEditable" class="table-responsive">
                <EasyDataTable
                    :loading="loading"
                    :headers="columns"
                    :items="rows"
                    theme-color="#556ee6"
                    show-index
                    table-class-name="no-border-table"
                    must-sort
                >
                    <template #empty-message>
                        <h5>Belum ada detail</h5>
                    </template>
                    <template #item-aksi="item">
                        <BButton
                            type="button"
                            variant="soft-danger"
                            size="sm"
                            @click.prevent="onDestroy(item.id)"
                        >
                            <i class="bx bx-trash"></i>
                        </BButton>
                    </template>
                </EasyDataTable>
            </div>
        </div>
    </b-modal>
</template>
<script>
import { mAlatService } from "@/services/MAlatService";
import { mBundleDetailService } from "@/services/MBundleDetailService";
import { mBundleService } from "@/services/MBundleService";
import { mKategoriService } from "@/services/MKategoriService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initFormData = () => ({
    id_kategori: "",
    nama: "",
    id: "",
});

const initFormDataDetail = () => ({
    id_bundle: 0,
    jml: 1,
    kode_alat: "",
});

export default {
    data() {
        return {
            modalForm: initFormData(),
            modalFormDetail: initFormDataDetail(),
            modalErr: {},
            modalTitle: "Tambah Unit",
            modalSubmit: false,
            modalEditable: false,
            modal: false,
            mKategoriList: [],
            mAlatList: [],
            columns: [
                {
                    text: "KD alat",
                    value: "kode_alat",
                },
                {
                    text: "Alat",
                    value: "alat",
                },
                {
                    text: "jml",
                    value: "jml",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            rows: [],
            loading: false,
        };
    },
    created() {
        this.getListKategori();
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        showModal() {
            this.modal = true;
            this.getAlat();
            if (this.modalEditable) {
                this.fetchData();
            }
        },
        hideModal() {
            this.$data.modalForm = initFormData();
            this.modalEditable = false;
            this.modal = false;
            this.resetForm();
        },
        resetForm() {
            this.modalForm = initFormData();
            this.modalErr = {};
        },
        resetFormDetail() {
            this.modalFormDetail = initFormDataDetail();
            this.modalErr = {};
        },
        onSubmit() {
            if (this.modalEditable) {
                this.onUpdate();
            } else {
                this.onStore();
            }
        },
        async fetchData() {
            this.show();
            const [err, resp] = await mBundleDetailService.data({
                id_bundle: this.modalForm.id,
            });
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.modalErr = err.response.data.errors;
                    this.hide(false);
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.hide();
            this.rows = resp.data;
        },
        async onSubmitDetail() {
            this.show();
            let payload = Object.assign({}, this.modalFormDetail, {
                id_bundle: this.modalForm.id,
            });
            console.log(payload);
            const [err] = await mBundleDetailService.store(payload);
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.modalErr = err.response.data.errors;
                    this.hide(false);
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.hide();
            this.resetFormDetail();
            this.fetchData();
        },
        async onStore() {
            this.show();
            const [err] = await mBundleService.store(this.modalForm);
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.modalErr = err.response.data.errors;
                    this.hide(false);
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.resetFormDetail();
        },
        async onUpdate() {
            this.show();
            const [err] = await mBundleService.update(
                this.modalForm,
                this.modalForm.kode
            );
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.modalErr = err.response.data.errors;
                    this.hide();
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.show();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.hideModal();
        },
        async getListKategori() {
            this.show();

            const [err, resp] = await mKategoriService.data();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.mKategoriList = resp.data;
            this.hide();
        },
        async getAlat() {
            this.show();
            const [err, resp] = await mAlatService.data();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.mAlatList = resp.data;
            this.hide();
        },
        async onDestroy(id) {
            this.show();
            const [err] = await mBundleDetailService.delete(id);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.hide();
            this.fetchData();
        },
    },
};
</script>