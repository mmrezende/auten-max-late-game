<template>
    <header>
        <div class="row align-items-center">
            <router-link
                :to="{name: 'home'}" v-show="isAdmin || !disable"
                class="col-1"
            >
                <img class="img-header-logo" src="@images/header_logo.png"/>
            </router-link>
            <div class="col-1" v-if="!isAdmin && disable">
                <img class="img-header-logo" src="@images/header_logo.png"/>
            </div>
            <nav class="col-10">
                <ul v-if="isAdmin">
                    <li><LinkIcon icon='emoji_events' url='tournaments'/></li>
                    <li><LinkIcon icon='person' url='users'/></li>
                    <li><LinkIcon icon='notifications' url='notifications'/></li>
                    <li><LinkIcon icon='request_quote' url='payments'/></li>
                    <li><LinkIcon icon='picture_in_picture' url='ads'/></li>
                    <li><LinkIcon icon='settings' url='settings'/></li>
                </ul>

                <ul v-else>
                    <li><LinkIcon icon='notifications' url='notifications' v-show="!disable"/></li>
                    <li><LinkIcon icon='emoji_events' url='tournaments' v-show="!disable"/></li>
                    <li><LinkIcon icon='person' url='profile'/></li>
                </ul>
            </nav>
            <div class="col-1">
                <a @click="this.logout"><LinkIcon icon='logout' url='/' :isRoute="false"/></a>
            </div>
        </div>
    </header>
    <div class="spacer"></div>
</template>

<script>
import axios from 'axios';
import { storeToRefs } from 'pinia';
import {useCurrentUserStore} from '../../stores/client';

export default {
    methods: {
        logout (e) {
            e.preventDefault();
            axios('/logout', {method: 'post'})
                .then(function (response) {
                    location.replace('/login')
                });
        }
    },
    props: {
        isAdmin: {
            type: Boolean,
            default: false
        },
        disable: Boolean
    }
}
</script>

<style scoped>
    header {
        width: 100%;
        position: fixed;
        z-index: 100;
        top: 0;
    }
    header > div {
        background: linear-gradient(180deg, #000000 0%, #141414 100%);
        height: 64px;
        padding: 0 64px;
    }

    .spacer {
        margin-bottom: 128px;
    }

    ul {
        list-style: none;
        display: flex;
        flex-direction: row;
        margin-bottom: 0;
        padding-left: 0;
        justify-content: center;
    }

    ul li {
        margin: 0 1rem;
    }

    .img-header-logo {
        height: 32px;
        width: auto;
    }

    nav {
        text-align: center;
    }
</style>
