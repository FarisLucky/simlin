import { http } from '@/config'

class HistoryDaftarService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/history/daftar?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/daftar/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async showBy(params) {
        try {
            const { data } = await this.http.get(`/daftar/show/by?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/daftar`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, kode) {
        try {
            const { data } = await this.http.put(`/daftar/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async updateAjukan(kode, form) {
        try {
            const { data } = await this.http.put(`/daftar/ajukan/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(id) {
        try {
            const { data } = await this.http.delete(`/daftar/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async deleteAll(ids) {
        try {
            const { data } = await this.http.delete(`/daftar-all${ids}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const historyDaftarService = new HistoryDaftarService(http)
