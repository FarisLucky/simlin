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
            <template #item-kategori="item">
                <span v-if="item.kategori !== '' && item.kategori !== null">
                    {{ item.kategori }}
                </span>
                <span v-else> SATUAN </span>
            </template>
            <template #item-bundle="item">
                <SelectOptions
                    v-if="
                        item.id_kategori !== null &&
                        status === STATUS_DAFTAR.TERIMA
                    "
                    :idKategori="item.id_kategori"
                    :idBundle="item.id_bundle"
                    :idPinjam="item.id"
                    @update="updateBundle"
                />
                <span
                    v-else-if="
                        item.id_bundle === 0 && item.id_kategori !== null
                    "
                >
                    BELUM SET BUNDLE
                </span>
                <span v-else-if="item.id_bundle === 0"> SATUAN </span>
                <span v-else> {{ item.bundle }} </span>
            </template>
            <template #item-detail="item">
                <ol class="p-0 ps-2">
                    <li v-for="(d, idx) in item.detail" :key="idx">
                        {{ `${d.nama}: ${d.jml} item` }}
                    </li>
                </ol>
            </template>
            <template #item-aksi="item">
                <BButton
                    v-if="
                        status === STATUS_DAFTAR.DITERIMA &&
                        status === STATUS_DAFTAR.NOTA &&
                        isSuperAdmin
                    "
                    type="button"
                    variant="soft-danger"
                    size="sm"
                    @click.prevent="onDestroy(item)"
                >
                    <i class="bx bx-trash"></i>
                </BButton>
                <div
                    v-if="status === STATUS_DAFTAR.DIKEMBALIKAN && isSuperAdmin"
                >
                    <BButton
                        type="button"
                        variant="soft-info"
                        size="sm"
                        @click.prevent="onCatatan(item.id)"
                        title="Simpan"
                    >
                        <i class="bx bx-notepad"></i>
                    </BButton>
                </div>
                <p v-if="item.catatan?.ket !== undefined" class="p-1">
                    note: <strong>{{ item.catatan?.ket }}</strong>
                </p>
            </template>
        </EasyDataTable>
        <DaftarEditListAlatNote ref="daftarNoteRef" />
    </div>
</template>
<script>
import { ROLE, STATUS_DAFTAR } from "@/helpers/utils";
import { pinjamService } from "@/services/PinjamService";
import { spinnerMethods, toastMethods } from "@/state/helpers";
import DaftarEditListAlatNote from "./DaftarEditListAlatNote.vue";
import SelectOptions from "@/components/SelectOptions.vue";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "nama",
    sortType: "ASC",
});

export default {
    components: {
        DaftarEditListAlatNote,
        SelectOptions,
    },
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
                    text: "Bundle",
                    value: "bundle",
                    sortable: true,
                },
                {
                    text: "Detail",
                    value: "detail",
                    sortable: false,
                    width: 120,
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
            bundles: [],
            STATUS_DAFTAR,
        };
    },
    computed: {
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    created() {
        this.fetchData();
        // this.getBundle();
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
            // let items = this.removeDuplicate(this.rows);
            this.loading = false;
        },
        async updateBundle(params) {
            this.show();
            console.log(params);
            let payload = {
                id_bundle: params?.id_bundle,
                bundle: params?.nama,
                id: params.id,
            };

            const [err] = await pinjamService.update(payload, payload.id);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });

                this.hide();
                return;
            }
            this.toastSuccess({
                title: "Berhasil",
                msg: "OK",
            });
            this.hide();
            // this.fetchData();
        },
        async onAcceptAll(params) {
            this.show();

            let items = params.map((item) => item.id);

            const [err] = await pinjamService.acceptAll(items);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });

                this.hide();
                return;
            }
            this.toastSuccess({
                title: "Berhasil",
                msg: "OK",
            });
            this.hide();
            this.fetchData();
        },
        onReset() {
            Object.assign(this.paginateOpt, initTableOpt());
            this.fetchData();
        },
        onCatatan(id) {
            let modal = this.$refs.daftarNoteRef;
            modal.modalTitle = "Catatan";
            modal.modalForm.id = id;
            modal.showModal();
        },
    },
};
</script>