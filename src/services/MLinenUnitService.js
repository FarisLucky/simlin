import { http } from '@/config'

class MLinenUnitService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/m-linen-unit?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {
            const { data } = await this.http.get(`/m-linen-unit/data/all?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/m-linen-unit/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/m-linen-unit`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, kode) {
        try {
            const { data } = await this.http.put(`/m-linen-unit/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(kode) {
        try {
            const { data } = await this.http.delete(`/m-linen-unit/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const mLinenUnitService = new MLinenUnitService(http)
