import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import jQuery from 'jquery';
window.$ = jQuery;

import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js';
window.bootstrap = bootstrap;
import 'admin-lte/dist/js/adminlte.js'

//Import seletec2
import select2 from 'select2'
select2()

//Import sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal;
