<template>
    <div class="row px-3">
        <table-col v-for="(field, index) in fields" :key="field.name" :width="field.width">
            <div class="title-col" :style="`border-left-color:${color};`" v-if="index === 0">
                <h4>{{ item[field.value] }}</h4>
            </div>
            <div v-else-if="field.hasImage">
                <img class="ad-thumbnail" :src="item[field.value]"/>
            </div>
            <div class="default-col" v-else>
                <h4>{{ item[field.value] }}</h4>
            </div>
        </table-col>

        <table-col :key="'action'" :width="actionWidth" justifyEnd v-if="!noAction">
            <div class="action-col" @click="defaultAction" v-if="!isEditable">
                <icon :name="defaultActionIcon" :color="color" size="1.5rem"/>
                <h4 :style="`color: ${color};`"> {{ defaultActionText }} </h4>
            </div>

            <div class="action-col" v-else>
                <span @click="actions.delete" v-if="actions.delete">
                    <icon :name="removeIcon" color="#EB4263" size="1.5rem"/>
                </span>
                <span @click="actions.edit" v-if="actions.edit" class="mx-3">
                    <icon name="mode" color="#B376F8" size="1.5rem"/>
                </span>
                <span @click="actions.approve" v-if="actions.approve">
                    <icon name="check" color="#05F28E" size="1.5rem"/>
                </span>
            </div>
        </table-col>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        color: String,
        defaultAction: Function,
        defaultActionIcon: String,
        defaultActionText: String,
        fields: Array,
        item: Object,
        actions: Object,
        isEditable: {
            type: Boolean,
            default: false
        },
        actionWidth: Number,
        noAction: Boolean,
        removeIcon: String
    }
}
</script>

<style scoped>
    .row {
        background-color: #232323;
        min-height: 4.25rem;
        margin-bottom: 8px;
        border-radius: 8px;
    }

    h4 {
        margin-bottom: 0;
    }

    .title-col {
        border-left: 2px solid transparent;
    }

    .title-col h4 {
        padding-left: 16px;
        color: #BFC9DB;
        font-weight: 700;
    }

    .action-col {
        cursor: pointer;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .action-col h4{
        font-weight: 600;
        padding-left: 8px;
    }

    .ad-thumbnail {
        height: 2.25rem;
        width: auto;
    }

    @media only screen and (max-width: 1366px) {
        .action-col h4, .title-col h4, .default-col h4 {
            font-size: 0.85rem;
        }
    }
</style>
