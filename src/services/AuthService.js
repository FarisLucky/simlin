import { http } from "@/config";

class AuthService {
  constructor(http) {
    this.http = http;
  }

  async store(form) {
    try {
      const { data } = await this.http.post(`/login/`, form);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }
  async logout() {
    try {
      const { data } = await this.http.delete(`/logout/`);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }
  async changePassword(form) {
    try {
      const { data } = await this.http.put(`/change-password/`, form);

      return [null, data];
    } catch (error) {
      return [error];
    }
  }
}

export const authService = new AuthService(http);
