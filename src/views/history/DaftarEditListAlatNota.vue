<template>
    <div class="mb-1">
        <EasyDataTable
            :loading="loading"
            :headers="columns"
            :items="rows"
            theme-color="#556ee6"
            show-index
            table-class-name="customize-table"
            must-sort
        >
            <template #empty-message>
                <h5>Belum ada alat</h5>
            </template>
            <template #item-catatan="item">
                {{ item.catatan?.ket }}
            </template>
            <template #item-bundle_detail="item">
                {{ item.catatan?.ket }}
            </template>
            <template #item-bundle="item">
                <span v-if="item.id_bundle === 0 && item.id_kategori !== null">
                    TIDAK SET BUNDLE
                </span>
                <span v-else-if="item.id_bundle === 0"> SATUAN </span>
                <span v-else> {{ item.bundle }} </span>
            </template>
            <template #item-detail="item">
                <ol v-if="item.detail.length > 0" class="p-0 ps-2">
                    <li v-for="(d, idx) in item.detail" :key="idx">
                        {{ `${d.nama}: ${d.jml} item` }}
                    </li>
                </ol>
                <div v-else class="p-1">-</div>
            </template>
            <template #item-kategori="item">
                <span v-if="item.kategori !== '' && item.kategori !== null">
                    {{ item.kategori }}
                </span>
                <span v-else> SATUAN </span>
            </template>
        </EasyDataTable>
    </div>
</template>
<script>
import { STATUS_DAFTAR } from "@/helpers/utils";
import { pinjamService } from "@/services/PinjamService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "nama",
    sortType: "ASC",
});

export default {
    props: ["status"],
    data() {
        return {
            columns: [
                {
                    text: "Nota",
                    value: "nota",
                    sortable: true,
                    fixed: true,
                },
                {
                    text: "Kategori",
                    value: "nama",
                    sortable: true,
                },
                {
                    text: "Bundle",
                    value: "bundle",
                    sortable: true,
                },
                {
                    text: "Detail",
                    value: "detail",
                    sortable: false,
                },
                {
                    text: "Tgl Pinjam",
                    value: "pinjam_cast",
                    sortable: false,
                    width: 120,
                },
                {
                    text: "Catatan",
                    value: "catatan",
                    sortable: false,
                },
            ],
            rows: [],
            rowsLength: 0,
            paginateOpt: initTableOpt(),
            loading: false,
            itemsSelected: [],
            STATUS_DAFTAR,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        async fetchData() {
            this.show();
            this.loading = true;

            const [err, resp] = await pinjamService.showByDaftar(
                this.$route.params.kode
            );
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.loading = false;

                this.hide();
                return;
            }
            this.hide();
            this.rows = resp.data;
            this.loading = false;
        },
        async onDeleteAll() {
            if (confirm("Apakah ingin dihapus ?")) {
                this.show();
                this.loading = true;
                let items = this.itemsSelected.map((item) => item.id);

                const [err] = await pinjamService.deleteAll(items);
                if (err) {
                    this.toastError({
                        title: "Gagal",
                        msg: err.response?.data?.errors,
                    });
                    this.loading = false;

                    this.hide();
                    return;
                }
                this.hide();
                this.itemsSelected = [];
                this.fetchData();
            }
        },
        onReset() {
            Object.assign(this.paginateOpt, initTableOpt());
            this.fetchData();
        },
    },
};
</script>