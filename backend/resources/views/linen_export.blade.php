<table>
    <tr>
        <td></td>
        <td colspan="5">REKAP Linen</td>
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
        <td colspan="5">Linen pada tanggal {{ \Carbon\Carbon::make($start)->format('d-m-Y') }} -
            {{ \Carbon\Carbon::make($end)->format('d-m-Y') }}</td>
    </tr>
</table>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Unit</th>
            <th>TERIMA</th>
            <th>KEMBALI</th>
            <th>SELESAI</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($linens as $linen)
            <tr>
                <td>{{ \Carbon\Carbon::make(optional($linen)->selesai_cast)->format('d-m-Y') }}</td>
                <td>{{ optional($linen)->nama_unit }}</td>
                <td>{{ optional($linen)->sum_terima }}</td>
                <td>{{ optional($linen)->sum_kembali }}</td>
                <td>{{ optional($linen)->sum_akhir }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
