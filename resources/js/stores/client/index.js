import { defineStore } from 'pinia';
import {parse, format} from 'date-format-parse';
import axios from 'axios';

// Tournaments
export const useTournamentStore = defineStore('tournament', {
    state: () => ({
        tournaments: [],
        errors: [],
        enabledTournaments: [],
    }),
    actions: {
        refresh() {
            axios
                .get('/api/tournament')
                .then((res) => {
                    this.errors = res.data.errors;
                    return res.data.data
                        .map(tournament =>{
                        const subscription = tournament.subscription.split(' ');
                        let begin = parse(subscription[0], 'HH:mm')
                        let end = parse(subscription[2], 'HH:mm')

                        begin = format(begin, 'HH:mm');
                        end = format(end, 'HH:mm');

                        return ({
                            ...tournament,
                            subscription: `${begin} ${subscription[1]} ${end}`
                        })
                    });
                })
                .then((tournaments) => {
                    this.tournaments = tournaments;
                    this.enabledTournaments = tournaments
                        .filter(item => item.isNotifiable)
                        .filter(item => { // Check if happens today
                            return parse(item.date, 'DD/MM/YYYY').toDateString() === new Date().toDateString()
                        });
                })
                .catch(e => console.error(e));
        }
    }
});

export const useTournamentTypeStore = defineStore('tournamentType', {
    state: () => ({
        tournamentTypes: [],
    }),
    actions: {
        refresh() {
            axios
                .get('/api/tournament_type')
                .then((res) => this.tournamentTypes = res.data.data)
                .catch(e => console.error(e));
        }
    }
});

export const useTournamentPlatformStore = defineStore('tournamentPlatform', {
    state: () => ({
        tournamentPlatforms: [],
    }),
    actions: {
        refresh() {
            axios
                .get('/api/tournament_platform')
                .then((res) => this.tournamentPlatforms = res.data.data)
                .catch(e => console.error(e));
        }
    }
});

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        due: [],
        timeouts: [],
    }),
    actions: {
        getNotificationTitle(notification) {
            if(notification.type === 'tournament') {
                return notification.tournament.name;
            }
            
            if(notification.type === 'administrative') {
                return 'Administração';
            }

            return 'Financeiro';
        },
        refresh() {
            axios
                .get('/api/notification')
                .then((res) => res.data.data)
                .then((data) => {
                    this.notifications = [];
                    this.due = [];
                    data.forEach(notification => {
                        let datetime = parse(notification.datetime, 'DD/MM/YYYY HH:mm');
                        const title = this.getNotificationTitle(notification);
                        if(datetime.getTime() <= Date.now()) { // Show notification history
                            this.notifications.push(
                                {
                                    ...notification,
                                    title,
                                    datetime: format(datetime, 'DD/MM/YYYY HH:mm')
                                }
                            );
                        }else {
                            this.due.push(
                                {
                                    ...notification,
                                    title,
                                    datetime
                                }
                            );
                        }
                    });
                })
                .then(this.schedule);
        },
        schedule() {
            // Clear all standard timeouts
            this.timeouts.forEach(timeout => {
                clearTimeout(timeout);
            });
            this.timeouts = [];

            const isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

            const platformIcon = isFirefox ? '/images/dark_logo.png' : '/images/logo.png';

            Notification
                .requestPermission()
                .then(permission => {
                    if (permission !== 'granted') {
                        return alert('É necessário permitir as notificações para fazer bom uso da plataforma.');
                    }

                    // Process notifications in the main tab

                    this.due.forEach(notification => {
                        const timeout = notification.datetime.getTime() - Date.now();

                        if(timeout <= 24*60*60*1000) {
                            this.timeouts.push(setTimeout(() => 
                                {
                                    new Notification(
                                        notification.title,
                                        {
                                            tag: notification.id, // a unique ID
                                            body: notification.type !== 'tournament' ? notification.description :
                                                `Inscrição: ${notification.tournament.subscription} \n` +
                                                `Plataforma: ${notification.tournament.platform_name}`,
                                                // content of the push notification
                                            data: {
                                                url: window.location.href, // pass the current url to the notification
                                            },
                                            badge: notification.tournament ? notification.tournament.platform_img : platformIcon,
                                            icon: notification.tournament ? notification.tournament.platform_img : platformIcon,
                                        }
                                    );

                                    this.refresh();
                                },
                                timeout
                            ));
                        }
                    });
                });
        }
    }
});

// NotificationIntervals
export const useNotificationIntervalStore = defineStore('notificationInterval', {
    state: () => ({
        notificationIntervals: [],
    }),
    actions: {
        refresh() {
            axios
                .get('/api/notification_interval')
                .then((res) => this.notificationIntervals = res.data.data)
                .then(() => {
                    this.notificationIntervals = this.notificationIntervals.map(interval => {
                        return ({
                            id: interval.minutes,
                            name: `${interval.minutes} minutos antes`
                        })
                    })
                })
                .catch(e => console.error(e))
        }
    }
});

// Current User data
import {getCurrentInstance} from 'vue';

export const useCurrentUserStore = defineStore('currentUser', {
    state: () => ({
        user: getCurrentInstance().appContext.config.globalProperties.user,
        isRegular: getCurrentInstance().appContext.config.globalProperties.user.isRegular
    }),
    actions: {
        refresh() {
            axios
                .get('/api/user/' + this.user.id)
                .then((res) => this.user = res.data.data)
                .then(() => this.isRegular = this.user.isRegular)
                .catch(e => console.error(e))
        }
    }
});

// NotificationIntervals
export const useAdStore = defineStore('ad', {
    state: () => ({
        ad: null,
    }),
    actions: {
        refresh() {
            axios
                .get('/api/ad')
                .then((res) => this.ad = (!res.data) ? null : res.data.data)
                .catch(e => console.error(e))
        }
    }
});