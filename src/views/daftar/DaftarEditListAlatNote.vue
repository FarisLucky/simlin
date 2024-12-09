<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        size="sm"
        hide-footer
    >
        <div class="p-1">
            <form @submit.prevent="onSubmit">
                <div class="mb-1">
                    <BFormTextarea
                        v-model="modalForm.ket"
                        placeholder="Alasan seperti pin kurang 1..."
                        rows="3"
                    ></BFormTextarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save-all"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </b-modal>
</template>
<script>
import { pinjamService } from "@/services/PinjamService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

export default {
    data() {
        return {
            modalForm: {
                id: "",
                ket: "",
            },
            modalTitle: "",
            modal: false,
        };
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        showModal() {
            this.modal = true;
        },
        hideModal() {
            this.modal = false;
        },
        async onSubmit() {
            this.show();
            const [err] = await pinjamService.storeCatatan(
                this.modalForm,
                this.modalForm.id
            );
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.err = err.response.data.errors;
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
            this.hideModal();
        },
    },
};
</script>