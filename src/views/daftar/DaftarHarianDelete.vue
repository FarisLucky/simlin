<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        hide-footer
        @close="hideModal"
    >
        <div class="mb-1">
            <h5>
                Apakah ingin menghapus data
                <strong class="text-info">{{ modalForm?.nama }}</strong>
            </h5>
        </div>
        <hr class="mb-2" />
        <div class="mb-1">
            <BButton variant="danger" @click.prevent="onDelete" class="w-100">
                <i class="bx bx-trash"></i>
                Hapus
            </BButton>
        </div>
    </b-modal>
</template>
<script>
import { daftarService } from "@/services/DaftarService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

export default {
    data() {
        return {
            modalTitle: "",
            modalUnit: "",
            modalForm: {},
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
            this.modalForm = {};
        },
        async onDelete() {
            this.show();
            const [err] = await daftarService.delete(this.modalForm?.id);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.$emit("fetch");
                this.hide();
                return;
            }
            this.toastSuccess({
                title: "Berhasil",
                msg: "OK",
            });
            this.hideModal();
            this.hide();
            this.$emit("fetch");
        },
    },
};
</script>
<style lang="">
</style>