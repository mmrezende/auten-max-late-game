<template>
    <div class="container-fluid">
        <div class="row table-header px-3">
            <table-col v-for="field in fields" :key="field.name" :width="field.width">
                <div>
                    <h4>{{ field.name }}</h4>
                </div>
            </table-col>

            <div :class="`col-${actionWidth}`" v-if="!noAction"></div>
        </div>

        <table-row
            v-for="item in items"
            :key="item.id"
            :fields="fields"
            :title="item.title"
            :color="colorPicker(item)"
            :item="item"
            :defaultAction="() => action(item)"
            :defaultActionIcon="defaultActionIcon || actionIcon(item)"
            :defaultActionText="defaultActionText || actionText(item)"
            :isEditable="isEditable(item)"
            :actions="{
                'delete': deleteAction ? () => deleteAction(item) : null,
                'edit': editAction ? () => editAction(item) : null,
                'approve': approveAction ? () => approveAction(item) : null,
            }"
            :actionWidth="actionWidth"
            :noAction="noAction || disableAction(item)"
            :removeIcon="removeIcon"
        />
    </div>
</template>

<script>
export default {
    props: {
        defaultActionIcon: String,
        defaultActionText: String,
        fields: Array,
        items: Array,
        approveAction: {
            type: Function,
            default: null
        },
        editAction: {
            type: Function,
            default: null
        },
        deleteAction: {
            type: Function,
            default: null
        },
        actionWidth: {
            type: Number,
            default: 1
        },
        noAction: {
            type: Boolean,
            default: false
        },
        colorPicker: {
            type: Function,
            default: () => null
        },
        action: {
            type: Function,
            default: () => null
        },
        actionText: {
            type: Function,
            default: () => null
        },
        actionIcon: {
            type: Function,
            default: () => null
        },
        removeIcon: {
            type: String,
            default: 'block'
        },
        disableAction: {
            type: Function,
            default: () => false
        },
        isEditable: {
            type: Function,
            default: () => null
        },
    }
}
</script>

<style scoped>
    .table-header {
        height: 2.75rem;
        background-color: #232323;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .table-header h4 {
        margin-bottom: 0;
        font-weight: 700;
        font-size: 1rem;
    }

    @media only screen and (max-width: 1366px) {
        .table-header h4 {
            font-size: 0.75rem;
        }
    }
</style>
