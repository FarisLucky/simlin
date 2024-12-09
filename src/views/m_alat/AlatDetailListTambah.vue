<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        size="lg"
        hide-footer
    >
        <form @submit.prevent="onSubmit">
            <BRow class="g-2 align-items-end">
                <BCol v-if="modalEditable" sm="2">
                    <label class="mb-1">Kode</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="modalForm.kode"
                        :disabled="modalEditable"
                    />
                    <span v-if="modalErr?.kode" class="text-danger">
                        {{ modalErr.kode[0] }}</span
                    >
                </BCol>
                <BCol sm="6" md="3">
                    <label class="mb-1">Kategori</label>
                    <v-select
                        v-model="modalForm.id_kategori"
                        :options="mKategoriList"
                        label="nama"
                        :reduce="(g) => g.id"
                        placeholder="Pilih Kategori"
                    >
                    </v-select>
                    <span v-if="modalErr?.id_kategori" class="text-danger">
                        {{ modalErr.id_kategori[0] }}</span
                    >
                </BCol>
                <BCol sm="6" md="3">
                    <label class="mb-1">Alat</label>
                    <v-select
                        v-model="modalForm.kode_alat"
                        :options="mAlatList"
                        label="nama"
                        :reduce="(g) => g.kode"
                        placeholder="Pilih Alat"
                        @option:selected="updateTotal"
                    >
                    </v-select>
                    <span v-if="modalErr?.kode_alat" class="text-danger">
                        {{ modalErr.kode_alat[0] }}</span
                    >
                </BCol>
                <BCol sm="4" md="3">
                    <label class="mb-1">
                        Total
                        <small v-if="sisa_text !== ''" class="text-danger">
                            ({{ sisa_text }})
                        </small>
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="modalForm.jml"
                        :max="sisa"
                    />
                    <span v-if="modalErr?.jml" class="text-danger">
                        {{ modalErr.jml[0] }}</span
                    >
                </BCol>
                <BCol sm="4" md="3">
                    <label class="mb-1">Steril</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="modalForm.steril"
                    />
                    <span v-if="modalErr?.steril" class="text-danger">
                        {{ modalErr.steril[0] }}</span
                    >
                </BCol>
                <BCol sm="3" md="3">
                    <label class="mb-1">Status</label>
                    <v-select
                        v-model="modalForm.status"
                        :options="[
                            {
                                id: 0,
                                label: 'NONAKTIF',
                            },
                            {
                                id: 1,
                                label: 'AKTIF',
                            },
                        ]"
                        :reduce="(st) => st.id"
                        placeholder="Pilih Status"
                    >
                    </v-select>
                    <span v-if="modalErr?.status" class="text-danger">
                        {{ modalErr.status[0] }}</span
                    >
                </BCol>
                <BCol>
                    <BButton
                        type="submit"
                        variant="primary"
                        :disabled="modalSubmit"
                    >
                        {{ modalSubmit ? "Sedang Proses" : "Simpan" }}
                    </BButton>
                </BCol>
            </BRow>
        </form>
    </b-modal>
</template>
<script>
import { mAlatDetailService } from "@/services/MBundleDetailService";
import { mAlatService } from "@/services/MAlatService";
import { mKategoriService } from "@/services/MKategoriService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initFormData = () => ({
    kode: "",
    kode_alat: "",
    nama: "",
    jml: "",
    steril: "",
    status: "",
    sisa_alat: "",
});

export default {
    data() {
        return {
            modalForm: initFormData(),
            modalErr: {},
            modalTitle: "Tambah Linen",
            modalSubmit: false,
            modalEditable: false,
            modal: false,
            mAlatList: [],
            mKategoriList: [],
            sisa_text: "",
            sisa: "",
        };
    },
    watch: {
        "modalForm.nama"(newValue) {
            this.modalForm.nama = newValue.toUpperCase();
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        showModal() {
            this.modal = true;
        },
        hideModal() {
            this.$data.modalForm = initFormData();
            this.modalEditable = false;
            this.modal = false;
        },
        resetForm() {
            this.modalForm = Object.assign({}, initFormData());
            this.modalErr = {};
        },
        onSubmit() {
            if (this.modalEditable) {
                this.onUpdate();
            } else {
                this.onStore();
            }
        },
        async getListAlat() {
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
        async onStore() {
            this.show();
            const [err] = await mAlatDetailService.store(this.modalForm);
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
            this.resetForm();
        },
        async onUpdate() {
            this.show();
            const [err] = await mAlatDetailService.update(
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
                this.hide();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.hideModal();
        },
        updateTotal(val) {
            this.sisa_text = `maks. ${val.sisa} item`;
            this.modalForm.sisa_alat = val.sisa;
        },
    },
};
</script>
<style lang="">
</style>