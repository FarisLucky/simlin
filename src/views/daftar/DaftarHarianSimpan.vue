<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        hide-footer
    >
        <form @submit.prevent="$emit('onSubmit', modalForm)">
            <BRow class="g-2">
                <BCol sm="12">
                    <div class="text-end">
                        <BButton
                            type="submit"
                            variant="primary"
                            :disabled="modalSubmit"
                        >
                            <i class="mdi mdi-content-save"></i>
                            {{ modalSubmit ? "Tunggu dulu" : "Simpan" }}
                        </BButton>
                    </div>
                </BCol>
            </BRow>
        </form>
    </b-modal>
</template>
<script>
import { spinnerMethods, toastMethods } from "@/state/helpers";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
// import { daftarService } from "@/services/DaftarService";

export default {
    data() {
        return {
            modalTitle: "Simpan Pengajuan",
            modalSubmit: false,
            modal: false,
            Indonesian,
            modalForm: {
                kode: "",
                progress: "",
                jenis: "",
            },
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
    },
};
</script>