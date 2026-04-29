/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import { createApp } from 'vue';
import adminRouter from './routes/admin';
import clientRouter from './routes/client';

import App from './views/App.vue';

// Load root component respectively to user privileges
const app = createApp(App);

// Provide user info to globally
app.config.globalProperties.user = {...PHP_USER, is_admin: Boolean(PHP_USER.is_admin)};

// Provide mercadoPago instance globally
app.config.globalProperties.mercadoPago = MERCADO_PAGO_PK
    ? new MercadoPago(MERCADO_PAGO_PK, {
        locale: 'pt-BR'
    })
    : null;

// Load route respectively to user privileges
app.use(PHP_USER.is_admin ? adminRouter : clientRouter);

//Register global components
const globalComponents = require.context('./views/components', false, /\.vue$/i)
globalComponents.keys().map(key => app.component(key.split('/').pop().split('.')[0], globalComponents(key).default))

//Register specific components
const adminComponents = require.context('./views/components/admin', true, /\.vue$/i)
adminComponents.keys().map(key => app.component('Admin' + key.split('/').pop().split('.')[0], adminComponents(key).default))

const clientComponents = require.context('./views/components/client', true, /\.vue$/i)
clientComponents.keys().map(key => app.component('Client' + key.split('/').pop().split('.')[0], clientComponents(key).default))

// Register v-select component
import vSelect from 'vue-select'

import "vue-select/dist/vue-select.css";
app.component('v-select', vSelect)

//Load pinia root store
import { createPinia } from 'pinia'

app.use(createPinia());

//Load modals plugin
import { vfmPlugin } from 'vue-final-modal'

app.use(vfmPlugin);

//Load masks plugin
import VueTheMask from 'vue-the-mask';
app.use(VueTheMask);

app.mount('#app');
