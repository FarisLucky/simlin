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
                <BCol v-if="modalEditable" sm="2">
                    <div class="mb-1">
                        <label>Kode</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="modalForm.kode"
                            :disabled="modalEditable"
                        />
                        <span v-if="modalErr?.kode" class="text-danger">
                            {{ modalErr.kode[0] }}</span
                        >
                    </div>
                </BCol>
                <BCol sm="7">
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
import { mAlatService } from "@/services/MAlatService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initFormData = () => ({
    kode: "",
    nama: "",
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
            const [err] = await mAlatService.store(this.modalForm);
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
            const [err] = await mAlatService.update(
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
    },
};
</script>
<style lang="">
</style>