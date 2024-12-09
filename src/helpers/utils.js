const JENIS = {
    LINEN: 'LINEN',
    ALAT: 'ALAT',
}

const STATUS_DAFTAR = {
    NOTA: 0,
    PENGAJUAN: 1,
    TERIMA: 2,
    DIKEMBALIKAN: 3,
    SELESAI: 4,
}

const ROLE = {
    SUPER_ADMIN: 'SUPER_ADMIN'
}

const formatInput = function (num) {
    if (!validation.isNumber(num)) {
        return ''
    }
    let number_string = num.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi)

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        let separator = sisa ? '.' : ''
        rupiah += separator + ribuan.join('.')
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah

    return rupiah
}
const validation = {
    isNumber: function (str) {
        var pattern = /^\d+\.?\d*$/
        return pattern.test(str) // returns a boolean
    },
}

const parseFormatInput = (num) => {
    let number = num.toString().replaceAll('.', '').replaceAll(',', '.')
    return parseInt(number)
}

export {JENIS, STATUS_DAFTAR, ROLE, formatInput, parseFormatInput}