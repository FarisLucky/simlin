import { http } from '@/config'

class TransLinenService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/trans-linen?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {
            const { data } = await this.http.get(`/trans-linen?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/trans-linen/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
    
    async showBy(params) {
        try {
            const { data } = await this.http.get(`/trans-linen/show/by?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/trans-linen`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, kode) {
        try {
            const { data } = await this.http.put(`/trans-linen/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(kode) {
        try {
            const { data } = await this.http.delete(`/trans-linen/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const transLinenService = new TransLinenService(http)
