import { http } from '@/config'

class MBundleDetailService {
    http = null

    constructor(http) {
        this.http = http
    }

    async all(params) {
        try {
            const { data } = await this.http.get(`/m-bundle-detail?${params}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async data(params) {
        try {

            const { data } = await this.http.get(`/m-bundle-detail/data/all?id_bundle=${params.id_bundle}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async show(id) {
        try {
            const { data } = await this.http.get(`/m-bundle-detail/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async store(form) {
        try {
            const { data } = await this.http.post(`/m-bundle-detail`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async update(form, id) {
        try {
            const { data } = await this.http.put(`/m-bundle-detail/${id}`, form)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }

    async delete(id) {
        try {
            const { data } = await this.http.delete(`/m-bundle-detail/${id}`)

            return [null, data]
        } catch (error) {
            return [error]
        }
    }
}

export const mBundleDetailService = new MBundleDetailService(http)
