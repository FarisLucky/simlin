import { http } from '@/config'

class MAlatService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/m-alat?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data() {
        try {
            const { data } = await this.http.get(`/m-alat/data/all`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/m-alat/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/m-alat`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, kode) {
        try {
            const { data } = await this.http.put(`/m-alat/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(kode) {
        try {
            const { data } = await this.http.delete(`/m-alat/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const mAlatService = new MAlatService(http)
