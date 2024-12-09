import { http } from '@/config'

class MLinenService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/m-linen?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {
            const { data } = await this.http.get(`/m-linen-data?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/m-linen/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/m-linen`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, kode) {
        try {
            const { data } = await this.http.put(`/m-linen/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(kode) {
        try {
            const { data } = await this.http.delete(`/m-linen/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const mLinenService = new MLinenService(http)
