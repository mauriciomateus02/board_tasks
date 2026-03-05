import axios from "axios"

const http = axios.create({
  baseURL: "http://localhost:8000/api"
})

http.interceptors.request.use(config => {

  const token = localStorage.getItem("token")

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  } else {
    console.log('No token found')
  }

  return config
})

export default http