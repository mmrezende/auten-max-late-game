<template>
    <div class="position-absolute banner">
        <ClientBanner :position="5"/>
    </div>
    <Section title="Próximos torneios" icon="emoji_events">
        <ClientTournamentsFilters
            @change="filters = $event; updateTournaments()"
        />

        <ClientTournamentsTable
            :tournaments="tournaments"
            @select="(tournament) => selectedTournament = tournament"
            @disable="disable = true"
        />
        <Paginator @click="qtd += 20;updateTournaments()" :visible="qtd <= tournaments.length"/>

        <ClientEnableNotificationModal
            v-if="!!selectedTournament && !disable"
            :tournament="selectedTournament"
            @close="selectedTournament = null; updateTournaments()"
        />

        <ClientDisableNotificationModal
            v-if="disable"
            :tournament="selectedTournament"
            @close="selectedTournament = null; disable = false; updateTournaments()"
        />
    </Section>
</template>

<script>
import { storeToRefs } from 'pinia';
import {useTournamentStore} from '../../stores/client';

export default {
    setup() {
        const tournamentStore = useTournamentStore();
        tournamentStore.refresh();

        const {tournaments} = storeToRefs(tournamentStore);
        return {
            tournaments,
            tournamentStore
        }
    },
    methods: {
        selectTournament(tournament) {
            this.selectedTournament = tournament;
        },
        updateTournaments() {
            this.tournamentStore.refresh(this.qtd, this.filters);
        }
    },
    data() {
        return {
            selectedTournament: null,
            disable: false,
            qtd: 7,
            filters: {}
        }
    },
}
</script>

<style scoped>
.banner {
    height: 168px;
    width: 45vw;
    top: 80px;
    right: 0;
    z-index: 50;
}
</style>
