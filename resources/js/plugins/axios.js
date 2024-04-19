import axios from 'axios'
import Swal from 'sweetalert2'
import router from "@/router"

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  // baseURL: 'http://127.0.0.1:8000/',

  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
})



// ℹ️ Add request interceptor to send the authorization header on each subsequent request after login
axiosIns.interceptors.request.use(config => {
  // Retrieve token from localStorage
  const token = localStorage.getItem('accessToken')
  if (token) {

    config.headers = config.headers || {}

    config.headers.Authorization = token ? `Bearer ${token}` : ''

  }

  // Return modified config
  return config
})

// ℹ️ Add response interceptor to handle 401 response
axiosIns.interceptors.response.use(response => {
  return response

}, error => {
  if(error.response.status == 401){
    router.push('/login')
  }

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: toast => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    },
  })

  if (error.response.data.errors != null) {
    // eslint-disable-next-line sonarjs/no-unused-collection
    const errorMessages = []
    for (const field in error.response.data.errors) {
      const fieldErrors = error.response.data.errors[field]

      errorMessages.push(...fieldErrors)
    }
    const concatenatedMessage = errorMessages.join('\n')

    Toast.fire({
      icon: 'error',
      title: concatenatedMessage,
    })
  }
  else {
    Toast.fire({
      icon: 'error',
      title: error.response.data.message != undefined ? error.response.data.message : error.response.data.error,
    })
  }


  return error
  router.push('/login')
})

/* if (error.response &&error.response.status === 401) {
    // ℹ️ Logout user and redirect to login page
    // Remove "userData" from localStorage
    localStorage.removeItem('userData')
    
    // Remove "accessToken" from localStorage
    localStorage.removeItem('accessToken')
    localStorage.removeItem('userAbilities')

    // If 401 response returned from api
    router.push('/login')
  }
  else {
    return Promise.reject(error)
  } */



export default axiosIns


