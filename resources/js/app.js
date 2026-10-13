import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

window.Alpine = Alpine;

Alpine.start();