import { http } from '@/config'

class TransLinenDetailService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/trans-linen-detail?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {
            const { data } = await this.http.get(`/trans-linen-detail?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/trans-linen-detail/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
    
    async showByDaftar(kodeDaftar) {
        try {
            const { data } = await this.http.get(`/trans-linen-detail/kode-daftar/${kodeDaftar}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/trans-linen-detail`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async updateQty(form, id) {
        try {
            const { data } = await this.http.put(`/trans-linen-detail/${id}/qty`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(kode) {
        try {
            const { data } = await this.http.delete(`/trans-linen-detail/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const transLinenDetailService = new TransLinenDetailService(http)
