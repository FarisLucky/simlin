import { http } from '@/config'

class PinjamService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/pinjam-alat?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {
            const { data } = await this.http.get(`/pinjam-alat?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/pinjam-alat/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
    
    async showByDaftar(kodeDaftar) {
        try {
            const { data } = await this.http.get(`/pinjam-alat/kode-daftar/${kodeDaftar}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/pinjam-alat`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async updateQty(form, id) {
        try {
            const { data } = await this.http.put(`/pinjam-alat/${id}/qty`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, id) {
        try {
            const { data } = await this.http.put(`/pinjam-alat/${id}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(id) {
        try {
            const { data } = await this.http.delete(`/pinjam-alat/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async deleteAll(ids) {
        try {
            const { data } = await this.http.delete(`/pinjam-alat-all?id=${ids}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async acceptAll(ids) {
        try {
            const { data } = await this.http.get(`/pinjam-alat/terima/all?id=${ids}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async storeCatatan(form, id) {
        try {
            const { data } = await this.http.put(`/pinjam-alat-catatan/${id}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const pinjamService = new PinjamService(http)
