<template>
    <div>
        <Header :is-admin="user.is_admin" :disable="!(this.user.is_admin || this.isRegular || !this.isPastTestPeriod)"/>
        <main class="mt-5">
            <router-view/>
        </main>
        <ClientAd v-if="!user.is_admin"/>
        <ClientFooter v-if="!user.is_admin"/>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia';
import {useNotificationStore, useCurrentUserStore, usePaymentPlanStore} from '../stores/client';
import {useBannerStore} from "../stores/admin";

export default {
    setup() {
        const currentUserStore = useCurrentUserStore();
        currentUserStore.refresh();
        const bannerStore = useBannerStore();
        bannerStore.refresh();
        const {user, isRegular, isPastTestPeriod} = storeToRefs(currentUserStore);

        return {
            user,
            isRegular,
            isPastTestPeriod,
            currentUserStore
        }
    },
    watch:{
        $route (to, from){
            this.currentUserStore.refresh();
        }
    },
    mounted() {
        if(!(this.user.is_admin || this.isRegular || !this.isPastTestPeriod)) {
            return this.$router.push({name: 'profile'});
        }

        if (!this.user.is_admin) {
            const notificationStore = useNotificationStore();
            notificationStore.refresh();
            const paymentPlanStore = usePaymentPlanStore();
            paymentPlanStore.refresh();

            setInterval(() => {
                notificationStore.refresh();
            }, 60 * 1 * 1000);
        }
    },
}
</script>

<style>
    .img-home {
        width: 7vw;
        height: auto;
        min-width: 180px;
    }

    .circle {
        width: .75rem;
        height: .75rem;
        border-radius: 50%;
    }

    .absolute-top-right {
        position: absolute;
        top: 0;
        right: 0;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        right: 1rem;

        transform: translateY(-50%);
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #BFC9DB;
        pointer-events: none;
    }

    input:autofill + .input-icon {
        color: #4F4F4F
    }

    .input-button {
        position: absolute;
        top: 50%;
        right: 1rem;

        transform: translateY(-50%);
        color: #F2F5FA;
    }

    .unselectable {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

        user-drag: none;
        -webkit-user-drag: none;
    }

    .thin-table {
        width: 100%;
    }

    button {
        outline: 0;
        border: 0;
        background-color: transparent;
        cursor: pointer;
    }

</style>


<style>

.v-custom *{
    cursor: pointer;
}

.v-custom .vs__search::placeholder,
.v-custom .vs__dropdown-toggle,
.v-custom .vs__dropdown-menu{
    background: #4F4F4F;
    border: none;
    color: #BFC9DB;
}

.v-custom .vs__selected {
    color: #BFC9DB;
}

.v-custom .vs__clear,
.v-custom .vs__open-indicator {
    fill: #BFC9DB;
}

.v-custom .vs__dropdown-toggle {
    height: 3rem;
    padding: 0 1rem;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.v-custom .vs__selected {
    background-color: #232323;
}

.v-custom .vs__dropdown-option--disabled { display: none; }

.v-custom .vs__deselect {
    fill: #EB4263;
}

</style>