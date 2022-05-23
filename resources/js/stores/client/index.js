import { defineStore } from 'pinia';
import {parse, format} from 'date-format-parse';
import {func} from '../../func';

// Tournaments
const useTournamentStore = defineStore('tournament', {
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
                    this.tournaments = res.data.data;
                    this.enabledTournaments = res.data.data.filter(item => item.isNotifiable);
                    this.errors = res.data.errors;
                })
                .then(() => {
                    this.tournaments = this.tournaments.map(tournament =>{
                        const subscription = tournament.subscription.split(' ');
                        let begin = parse(subscription[0], 'HH:mm')
                        let end = parse(subscription[2], 'HH:mm')
                        begin = func.toLocal(begin);
                        end = func.toLocal(end);

                        begin = format(begin, 'HH:mm');
                        end = format(end, 'HH:mm');

                        return ({
                            ...tournament,
                            subscription: `${begin} ${subscription[1]} ${end}`
                        })
                    });
                })
                .catch(e => console.error(e));
        }
    }
});

const useTournamentTypeStore = defineStore('tournamentType', {
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

const useTournamentPlatformStore = defineStore('tournamentPlatform', {
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

const useNotificationStore = defineStore('notification', {
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
                    data.forEach(notification =>{
                        let datetime = parse(notification.datetime, 'DD/MM/YYYY HH:mm');
                        const title = this.getNotificationTitle(notification);
                        if(datetime <= Date.now()) { // Show notification history
                            this.notifications.push(
                                {
                                    ...notification,
                                    title,
                                    datetime: format(func.toLocal(datetime), 'DD/MM/YYYY HH:mm')
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
                });
            
            this.schedule();
        },
        schedule() {
            navigator.serviceWorker.getRegistration(workerPath)
                .then(reg => {
                    console.log(reg);
                    Notification.requestPermission()
                    .then(permission => {
                        if (permission !== 'granted') {
                            return alert('É necessário permitir as notificações para fazer bom uso da plataforma.');
                        }

                        if(!!reg){
                            this.due.forEach(notification => {                
                                const timeout = notification.datetime.getTime() - Date.now();
                
                                setTimeout(() => 
                                    {
                                        reg.showNotification(
                                            notification.title,
                                            {
                                                tag: notification.id, // a unique ID
                                                body: notification.type !== 'tournament' ? notification.description : `
                                                    Inscrição: ${notification.tournament.subscription}
                                                    Plataforma: ${notification.tournament.platform_name}
                                                `, // content of the push notification
                                                //showTrigger: new TimestampTrigger(timestamp), // set the time for the push notification
                                                data: {
                                                    url: window.location.href, // pass the current url to the notification
                                                },
                                                badge: './assets/badge.png',
                                                icon: './assets/icon.png',
                                            }
                                        );
                                    },
                                    3000
                                );
                            });
                        }else {
                            this.due.forEach(notification => {                
                                const timeout = notification.datetime.getTime() - Date.now();
                
                                setTimeout(() => 
                                    {
                                        reg.showNotification(
                                            notification.title,
                                            {
                                                tag: notification.id, // a unique ID
                                                body: notification.type !== 'tournament' ? notification.description : `
                                                    Inscrição: ${notification.tournament.subscription}
                                                    Plataforma: ${notification.tournament.platform_name}
                                                `, // content of the push notification
                                                //showTrigger: new TimestampTrigger(timestamp), // set the time for the push notification
                                                data: {
                                                    url: window.location.href, // pass the current url to the notification
                                                },
                                                badge: './assets/badge.png',
                                                icon: './assets/icon.png',
                                            }
                                        );
                                    },
                                    3000
                                );
                            });
                        }
                    });
                });

        }
    }
});

// NotificationIntervals
const useNotificationIntervalStore = defineStore('notificationInterval', {
    state: () => ({
        notificationIntervals: [],
    }),
    actions: {
        refresh() {
            return (
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
            );
        }
    }
});

export {useTournamentStore, useTournamentTypeStore, useTournamentPlatformStore, useNotificationStore, useNotificationIntervalStore};