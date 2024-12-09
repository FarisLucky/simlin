<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        size="lg"
        hide-footer
    >
        <div class="px-2 py-1 d-inline-block bg-light rounded mb-1">
            <h5 class="m-0 text-success" v-if="terisi === 'ya'">
                {{ terisi.toUpperCase() }}
            </h5>
            <h5 class="m-0 text-danger" v-else>
                {{ terisi.toUpperCase() }} TERISI
            </h5>
        </div>
        <form @submit.prevent="onUpdate">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="4">
                                <div class="text-center">
                                    <strong class="fs-14">
                                        Kualitas linen Beri tanda ceklist
                                        <i
                                            class="bx bx-check-square"
                                            style="font-size: 18px"
                                        ></i>
                                        jika terlaksana
                                    </strong>
                                </div>
                            </th>
                            <th colspan="2">
                                <div class="text-center">
                                    <strong class="fs-14">
                                        Kualitas Baik jika keempatnya terpenuhi
                                    </strong>
                                </div>
                            </th>
                            <th rowspan="2">
                                <div class="text-center">
                                    <strong class="fs-14">
                                        Jumlah linen yang tidak terpenuhi
                                        kualitasnya
                                    </strong>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Tidak ada Noda</th>
                            <th>Tidak Berbau</th>
                            <th>Warna linen tidak pudar</th>
                            <th>Kerapian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_noda"
                                        value="ya"
                                        id="noda-ya"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="noda-ya"
                                    >
                                        YA
                                    </label>
                                </div>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_noda"
                                        value="tidak"
                                        id="noda-tidak"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="noda-tidak"
                                    >
                                        TIDAK
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_bau"
                                        value="ya"
                                        id="bau-ya"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="bau-ya"
                                    >
                                        YA
                                    </label>
                                </div>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_bau"
                                        value="tidak"
                                        id="bau-tidak"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="bau-tidak"
                                    >
                                        TIDAK
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_pudar"
                                        value="ya"
                                        id="pudar-ya"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="pudar-ya"
                                    >
                                        YA
                                    </label>
                                </div>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_pudar"
                                        value="tidak"
                                        id="pudar-tidak"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="pudar-tidak"
                                    >
                                        TIDAK
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_rapi"
                                        value="ya"
                                        id="rapi-ya"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="rapi-ya"
                                    >
                                        YA
                                    </label>
                                </div>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_rapi"
                                        value="tidak"
                                        id="rapi-tidak"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="rapi-tidak"
                                    >
                                        TIDAK
                                    </label>
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_kualitas"
                                        value="ya"
                                        id="kualitas-ya"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="kualitas-ya"
                                    >
                                        YA
                                    </label>
                                </div>
                                <div class="form-check form-radio-primary">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="mutu.tdk_kualitas"
                                        value="tidak"
                                        id="kualitas-tidak"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="kualitas-tidak"
                                    >
                                        TIDAK
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <input
                                            type="number"
                                            class="form-control form-control-sm"
                                            v-model="mutu.jml_rusak"
                                        />
                                    </div>
                                    <div class="col-6">
                                        <h4 class="d-inline-block m-0">
                                            /
                                            {{ mutu.ttl }}
                                        </h4>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td class="text-center">
                                <button class="btn btn-primary">
                                    <i class="bx bxs-save me-1"></i>
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </b-modal>
</template>
<script>
import { STATUS_DAFTAR } from "@/helpers/utils";
import { mutuService } from "@/services/MutuService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

export default {
    props: ["status", "pcreated_by", "jenisTrans"],
    data() {
        return {
            modalTitle: "",
            modalUnit: "",
            modalForm: {
                id: null,
                kode_daftar: null,
            },
            mutu: {
                id: null,
                kode_daftar: null,
                tdk_noda: "ya",
                tdk_bau: "ya",
                tdk_pudar: "ya",
                tdk_rapi: "ya",
                tdk_kualitas: "ya",
                jml_rusak: 0,
                ttl: 0,
            },
            terisi: "tidak",
            modal: false,
            lists: [],
            err: [],
            STATUS_DAFTAR,
        };
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        showModal() {
            this.modal = true;
            this.fetchData();
        },
        hideModal() {
            this.modal = false;
        },
        // async onDelete() {
        //     this.show();

        //     const [err] = await pinjamDetailService.delete(this.modalForm.id);
        //     if (err) {
        //         this.toastError({
        //             title: "Gagal",
        //             msg: err.response?.data?.errors,
        //         });
        //         this.hide();
        //         return;
        //     }
        //     this.hide();
        //     this.$emit("fetch");
        //     this.hideModal();
        // },
        async fetchData() {
            this.show();
            const [err, resp] = await mutuService.showByDaftar(
                this.modalForm.kode_daftar
            );
            if (err) {
                this.hide();
                this.terisi = "tidak";
                return;
            }
            this.mutu = resp.data;
            this.terisi = "ya";
            this.hide();
        },
        async onUpdate() {
            this.show();
            const [err, resp] = await mutuService.update(
                this.mutu,
                this.mutu.id
            );
            if (err) {
                this.hide();
                this.terisi = "tidak";
                return;
            }
            this.toastSuccess({
                title: "Gagal",
                msg: "OK",
            });
            this.mutu = resp.data;
            this.terisi = "ya";
            this.hide();
        },
    },
};
</script>
<style scoped>
table tr th {
    vertical-align: middle;
}
.modal-body {
    overflow-x: auto;
}
</style>