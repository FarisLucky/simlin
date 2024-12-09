<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        hide-footer
    >
        <form @submit.prevent="onSubmit">
            <BRow class="g-2 align-items-end">
                <BCol sm="8">
                    <div class="mb-1">
                        <label>Nama</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="modalForm.nama"
                            ref="namaRef"
                        />
                        <span v-if="modalErr?.nama" class="text-danger">
                            {{ modalErr.nama[0] }}</span
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
                            {{ modalSubmit ? "Sedang Proses" : "Simpan" }}
                        </BButton>
                    </div>
                </BCol>
            </BRow>
        </form>
    </b-modal>
</template>
<script>
import { mKategoriService } from "@/services/MKategoriService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initFormData = () => ({
    id: "",
    nama: "",
});

export default {
    data() {
        return {
            modalForm: initFormData(),
            modalErr: {},
            modalTitle: "Tambah Kategori",
            modalSubmit: false,
            modalEditable: false,
            modal: false,
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
        async onStore() {
            this.show();
            const [err] = await mKategoriService.store(this.modalForm);
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
            const [err] = await mKategoriService.update(
                this.modalForm,
                this.modalForm.id
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
    },
};
</script>
<style lang="">
</style>