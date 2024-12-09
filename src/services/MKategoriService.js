import { http } from '@/config'

class MKategoriService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/m-kategori?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data() {
        try {
            const { data } = await this.http.get(`/m-kategori/data/all`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/m-kategori/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/m-kategori`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, kode) {
        try {
            const { data } = await this.http.put(`/m-kategori/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(kode) {
        try {
            const { data } = await this.http.delete(`/m-kategori/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const mKategoriService = new MKategoriService(http)
