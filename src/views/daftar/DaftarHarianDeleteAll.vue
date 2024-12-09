<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        hide-footer
    >
        <div class="mb-1">
            <h5>
                Apakah ingin menghapus data
                <div class="text-info">
                    <span v-for="(item, idx) in items" :key="idx">
                        {{ item?.kode }},
                    </span>
                </div>
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
            items: [],
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
        async onDelete() {
            this.show();

            let params = "";
            this.items.forEach((item) => (params += `${item.id},`));

            const [err] = await daftarService.deleteAll("?ids=" + params);
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
            this.hide();
            this.$emit("fetch");
            this.hideModal();
        },
    },
};
</script>
<style lang="">
</style>