<template>
    <InputContainer :name="label">
        <div class="position-relative">
            <input type="email" @input="$emit('update:modelValue',$event.target.value)" :value="modelValue" :placeholder="placeholder" autocomplete="username"/>
            <div class="input-icon">
                <Icon name="mail"/>
            </div>
        </div>
    </InputContainer>
</template>

<script>
export default {
    props: {
        modelValue: String,
        label: {
            type: String,
            default: "Email",
        },
        placeholder: {
            type: String,
            default: null
        }
    },
}
</script>

<style scoped>
    input {
        height: 3rem;
        border-radius: .5rem;
        width: 100%;

        background-color: #4F4F4F;
        color: #BFC9DB;
        padding: 0 1rem;
        box-sizing: border-box;

        border: 0;
        outline: 0;
    }
</style>