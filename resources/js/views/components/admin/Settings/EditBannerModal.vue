<template>
	<Modal
        modalTitle= "Editar Banner"
        modalIcon= "picture_in_picture"
        submitModalText= "Cadastrar"
        :width="50"
        @submit="submit"
        ref="modal"
        noButton
        @close="$emit('close')"
        titleColor="#B376F8"
        :isLoading="isLoading"
	>
        <div class="row mb-3">
            <div class="col-6">
                <TextInput
                    label="Título *"
                    v-model="inputs.title"
                />
            </div>
            <div class="col-6">
                <FileInput
                    label="Imagem do banner *"
                    @change="(img) => inputs.img = img"
                    hint="Selecione uma imagem .png, .jpg ou .gif."
                    mime="image/jpeg,image/png,image/gif"
                />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <DisabledInput
                    label="Posição *"
                    :value="banner.position"
                />
            </div>
            <div class="col-8">
                <TextInput
                    label="Link do banner *"
                    v-model="inputs.link_url"
                    hasIcon
                    icon="link"
                />
            </div>
        </div>
    </Modal>
</template>

<script>
import {
    useBannerStore
} from '../../../../stores/admin';
import {mapState, mapStores} from 'pinia';
import DisabledInput from "../../DisabledInput";

export default {
    components: {DisabledInput},
    props: ['banner'],
    created() {
        this.positions = [
            {name: '1', id: 1},
            {name: '2', id: 2},
            {name: '3', id: 3},
            {name: '4', id: 4},
            {name: '5', id: 5},
        ]
        this.bannerStore.refresh();
    },
    mounted() {
        this.$refs.modal.openModal();
    },
    data() {
        return {
            isLoading: false,
            inputs: {
                title: this.banner.title,
                position: this.banner.position,
                img: null,
                link_url: this.banner.link_url,
            }
        }
    },
    computed: {
        ...mapState(useBannerStore, ['banners']),
        ...mapStores(useBannerStore),
    },
    methods: {
        submit() {
            this.isLoading = true;
            const formData = new FormData();
            formData.append('_method','PUT');

            for ( let key in this.inputs ) {
                if(!this.inputs[key]) continue;
                formData.append(key, this.inputs[key]);
            }

            axios
                .post(`/api/banner/${this.banner.id}`,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(this.$refs.modal.closeModal)
				.then(() => {
					this.inputs = {
                        title: null,
                        position: null,
                        img: null,
                        link_url: null,
                    }
				})
                .catch(() => {alert("Verifique os dados inseridos.")})
                .finally(() => {
                    this.bannerStore.refresh();
                    this.isLoading = false;
                });
        }
    }
}
</script>

<style scoped>
    h4 {
        font-weight: 400;
        color: #E2E2FF;
    }

    h5 {
        color: #E2E2FF;
    }
</style>
