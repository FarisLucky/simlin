<table>
    <tr>
        <td></td>
        <td colspan="5">REKAP Mutu Linen</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5">RS GRAHA SEHAT KRAKSAAN</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5">Jl Panglima Sudirman No 02</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="5">Mutu Linen pada tanggal {{ \Carbon\Carbon::make($start)->format('d-m-Y') }} -
            {{ \Carbon\Carbon::make($end)->format('d-m-Y') }}</td>
    </tr>
</table>
<table>
    <thead>
        <tr>
            <th>Nota Pengajuan</th>
            <th>Kode Daftar</th>
            <th>Unit</th>
            <th>TIDAK NODA</th>
            <th>TIDAK BAU</th>
            <th>TIDAK PUDAR</th>
            <th>TIDAK RAPI</th>
            <th>TIDAK KUALITAS</th>
            <th>Jumlah linen yang tidak terpenuhi kualitasnya</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mutu as $m)
            <tr>
                <td>{{ \Carbon\Carbon::make($m->pengajuan)->format('d-m-Y') }}</td>
                <td>{{ optional($m)->kode_daftar }}</td>
                <td>{{ optional($m)->nama_unit }}</td>
                <td>{{ optional($m)->tdk_noda }}</td>
                <td>{{ optional($m)->tdk_bau }}</td>
                <td>{{ optional($m)->tdk_pudar }}</td>
                <td>{{ optional($m)->tdk_rapi }}</td>
                <td>{{ optional($m)->tdk_kualitas }}</td>
                <td>{{ optional($m)->jml_rusak . '/' . optional($m)->ttl }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
