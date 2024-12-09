import axios from "axios";
import Cookies from "js-cookie";
// import { useToastStore } from '@/store/toast'

// const url = "http://localhost/simlin/backend/api"; //WEB APACHE
// const webUrl = "http://localhost/simlin/backend"; //WEB APACHE
const url = "https://rsgsit.my.id/simlin/backend/api"; //WEB APACHE
const webUrl = "https://rsgsit.my.id/simlin/backend"; //WEB APACHE
// const url = "http://192.168.3.208/simlin/backend/api"; //WEB APACHE
// const webUrl = "http://192.168.3.208/simlin/backend"; //WEB APACHE

const token =
  Cookies.get("e-linen") != undefined ? JSON.parse(Cookies.get("e-linen")).token : "";

const http = axios.create({
  baseURL: url,
  headers: {
    Authorization: "Bearer " + token,
    Accept: "application/json",
  },
  withCredentials: true,
});

http.interceptors.request.use(
  (x) => {
    // to avoid overwriting if another interceptor
    // already defined the same object (meta)

    return x;
  },
  (error) => {
    return Promise.reject(error.message);
  }
);

http.interceptors.response.use(
  (x) => {
    return x;
  },
  (error) => {
    if (error.response.status === 401) {
      Cookies.remove("e-linen");
      console.log('test')
      window.location.reload();
    }
    return Promise.reject(error);
  }
);

export { http, url, webUrl };
