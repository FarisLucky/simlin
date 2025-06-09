import { http } from "@/config";

class MBundleService {
  http = null;

  constructor(http) {
    this.http = http;
  }

  async all(params) {
    try {
      console.log(params);
      const { data } = await this.http.get(`/m-bundle?${params}`);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }

  async data(search) {
    try {
      const { data } = await this.http.get(`/m-bundle/data/all?${search}`);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }

  async getAlatTersedia(search) {
    try {
      const { data } = await this.http.get(
        `/m-bundle/data/all?${search}&tersedia=true`
      );

      return [null, data];
    } catch (error) {
      return [error];
    }
  }

  async show(id) {
    try {
      const { data } = await this.http.get(`/m-bundle/${id}`);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }

  async store(form) {
    try {
      const { data } = await this.http.post(`/m-bundle`, form);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }

  async update(form, id) {
    try {
      const { data } = await this.http.put(`/m-bundle/${id}`, form);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }

  async delete(id) {
    try {
      const { data } = await this.http.delete(`/m-bundle/${id}`);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }
}

export const mBundleService = new MBundleService(http);
