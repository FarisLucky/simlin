import { http } from '@/config'

class PinjamDetailService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/pinjam-detail?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {
            const { data } = await this.http.get(`/pinjam-detail?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/pinjam-detail/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
    
    async showByDaftar(kodeDaftar) {
        try {
            const { data } = await this.http.get(`/pinjam-detail/kode-daftar/${kodeDaftar}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/pinjam-detail`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async updateQty(form, id) {
        try {
            const { data } = await this.http.put(`/pinjam-detail/${id}/qty`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(id) {
        try {
            const { data } = await this.http.delete(`/pinjam-detail/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const pinjamDetailService = new PinjamDetailService(http)
