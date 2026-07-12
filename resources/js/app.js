import './bootstrap';
import Toast from './utils/toast.js';
import api from './utils/api.js';
import { initGlobalSearch } from './utils/search.js';

window.Toast = Toast;
window.api   = api;

document.addEventListener('DOMContentLoaded', () => {
    initGlobalSearch();
});
