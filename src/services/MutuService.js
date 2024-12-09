import { http } from '@/config'

class MutuService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/mutu?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(kode) {
        try {
            const { data } = await this.http.get(`/mutu/${kode}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async showByDaftar(kode_daftar) {
        try {
            const { data } = await this.http.get(`/mutu/${kode_daftar}/daftar`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/mutu`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, id) {
        try {
            const { data } = await this.http.put(`/mutu/${id}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async updatePengajuan(kode, form) {
        try {
            const { data } = await this.http.put(`/mutu/ajukan/${kode}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(id) {
        try {
            const { data } = await this.http.delete(`/mutu/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async deleteAll(ids) {
        try {
            const { data } = await this.http.delete(`/mutu-all${ids}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const mutuService = new MutuService(http)
