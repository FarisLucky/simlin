<template>
    <v-select
        v-model="id"
        :options="listOpt"
        :reduce="(bundle) => bundle.id"
        @option:selected="updateBundle"
        label="nama"
    >
    </v-select>
</template>
<script>
import { mBundleService } from "@/services/MBundleService";
import { spinnerMethods, toastMethods } from "@/state/helpers";
import queryString from "query-string";

export default {
    props: ["idBundle", "idKategori", "idPinjam"],
    data() {
        return {
            listOpt: [],
            id: "",
        };
    },
    async created() {
        this.getBundle();
        this.id = this.idBundle;
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        async getBundle() {
            this.show();

            let query = queryString.stringify({
                kategori: this.idKategori,
            });

            const [err, resp] = await mBundleService.getAlatTersedia(query);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });

                this.hide();
                return;
            }
            this.hide();
            this.listOpt = [];
            resp.data.forEach((bundle) => {
                this.listOpt.push({
                    id: bundle.id,
                    nama: bundle.nama,
                });
            });
        },
        updateBundle(ev) {
            this.$emit("update", {
                id: this.idPinjam,
                id_bundle: ev.id,
                nama: ev.nama,
            });
        },
    },
};
</script>
<style lang=""></style>