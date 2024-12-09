<template>
    <div class="mb-1">
        <div class="mb-1 text-end">
            <BButton
                v-if="itemsSelected.length > 0"
                variant="danger"
                @click.prevent="onDeleteAll(itemsSelected)"
            >
                <i class="bx bx-trash-alt"></i>
                Hapus All
            </BButton>
        </div>
        <EasyDataTable
            v-model:items-selected="itemsSelected"
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
            <template #item-kategori="item">
                <span v-if="item.kategori !== '' && item.kategori !== null">
                    {{ item.kategori }}
                </span>
                <span v-else> SATUAN </span>
            </template>
            <template #item-aksi="item">
                <BButton
                    type="button"
                    variant="soft-danger"
                    size="sm"
                    @click.prevent="onDestroy(item)"
                >
                    <i class="bx bx-trash"></i>
                </BButton>
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
                    text: "Nama",
                    value: "nama",
                    sortable: true,
                },
                {
                    text: "Tgl Pinjam",
                    value: "pinjam_cast",
                    sortable: false,
                    width: 120,
                },
                {
                    text: "Aksi",
                    value: "aksi",
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