<template>
    <div class="form-container">
        <EditForm
            :showSubmitButton="hasChanged"
            title="Pagamento"
            submitText="Alterar método de pagamento"
            @submit="submit"
        >
            <div class="d-flex flex-row gap-4 mb-3">
                <RadioGroup
                    :options="paymentMethods"
                    v-model="inputs.paymentMethod"
                />
            </div>

            <div v-if="!currentUser.isRegular">
                <div class="ticket-form" v-if="currentUser.payment_method === paymentMethods[0].value && !hasChanged">
                    <a class="text-decoration-none" v-if="!ticketLoading" :href="ticket_url" download="boleto.pdf" target="_blank">
                        Fazer download do boleto <Icon name="download"/>
                    </a>
                    <h4 v-else>Gerando fatura...</h4>
                    <p class="col-12 mb-4 mt-4">
                        Depois de efetuado, o pagamento será compensado em até dois dias úteis.
                    </p>
                    <p class="col-12">
                        Após a compensação, seu acesso à plataforma será reabilitado.
                    </p>
                </div>

                <div class="credit-card-form" v-else-if="currentUser.payment_method === paymentMethods[1].value && !hasChanged">
                    <div class="row mb-4">
                        <p class="col-12 mb-4">
                            Por motivos de segurança, nossa plataforma não armazena nenhum dos dados digitados abaixo.
                        </p>
                        <div class="col-6">
                            <TextInput
                                label="Número do cartão"
                                v-model="inputs.cardNumber"
                            />
                        </div>
                        <div class="col-6">
                            <TextInput
                                label="Nome do titular"
                                v-model="inputs.cardholderName"
                            />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <TextInput
                                label="CPF/CNPF do titular"
                                v-model="inputs.identificationNumber"
                            />
                        </div>
                        <div class="col-3">
                            <DateInput
                                label="Validade"
                                monthPicker
                                v-model="inputs.expireDate"
                            />
                        </div>
                        <div class="col-3">
                            <TextInput
                                name="CVV"
                                v-model="inputs.cvv"
                            />
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-5">
                        <DynamicButton @click="creditCardTransaction" text="Realizar pagamento" type="button"/>
                    </div>
                </div>
                <div class="manual-form" v-else-if="currentUser.payment_method === paymentMethods[2].value && !hasChanged">
                    <h4>Nossa chave pix:</h4>
                    <CopyButton
                        text="46715964000117"
                    />
                    <h4 class="my-4">Quando a transferência for realizada, envie o comprovante para o nosso WhatsApp: </h4>
                    <DynamicButton
                        text="Ir para o WhatsApp"
                        type="button"
                        @click.prevent="openWhatsapp"
                    />
                </div>
                <div class="manual-form" v-else-if="currentUser.payment_method === paymentMethods[3].value && !hasChanged">
                    <h4>Nosso código é:</h4>
                    <CopyButton
                        text="19994933553"
                    />
                    <h4 class="my-4">Quando a transferência for realizada, envie o comprovante para o nosso WhatsApp: </h4>
                    <DynamicButton
                        text="Ir para o WhatsApp"
                        type="button"
                        @click.prevent="openWhatsapp"
                    />
                </div>
            </div>
            <p v-else>Nenhuma fatura pendente encontrada.</p>
        </EditForm>
    </div>
</template>

<script>
import { storeToRefs } from "pinia";

import {useCurrentUserStore} from '../../../../stores/client';
import DynamicButton from "../../DynamicButton";
import StaticButton from "../../StaticButton";

export default {
    components: {StaticButton, DynamicButton},
    setup() {
        const currentUserStore = useCurrentUserStore();
        const { user } = storeToRefs(currentUserStore);
        return {
            currentUser: user,
            currentUserStore
        };
    },
    created() {
        this.paymentMethods = [
            {
                value: "bolbradesco",
                text: "Boleto",
            },
            {
                value: "credit_card",
                text: "Cartão de crédito",
            },
            {
                value: "pix",
                text: "Pix",
            },
            {
                value: "muchbetter",
                text: "MuchBetter",
            }
        ];

        this.generateTicket();
    },
    data() {
        return {
            inputs: {
                paymentMethod: this.currentUser.payment_method,
                cardNumber: null,
                cardholderName: null,
                identificationNumber: null,
                expireDate: null,
                cvv: null,
            },
            ticket_url: null,
            ticketLoading: true,
        };
    },
    methods: {
        openWhatsapp() {
            window.open('https://api.whatsapp.com/send?phone=5519992153608&text=Ol%C3%A1,%20gostaria%20de%20tirar%20algumas%20d%C3%BAvidas%20sobre%20a%20plataforma%20MLG!');
        },
        submit() {
            const method = this.inputs.paymentMethod;
            const res = confirm("Tem certeza que deseja alterar o plano de pagamento para \n" + this.paymentMethods.find((val) => val.value === method).text + " ?");
            if (!res)
                return;
            axios
                .put("/api/user/" + this.currentUser.id + "/payment_plan", {
                    method: method
                })
                .then(() => this.currentUserStore.refresh())
                .then(() => {
                    if(method === 'bolbradesco'){
                        this.currentUser.payment_method = 'bolbradesco';
                        this.generateTicket();
                    }
                })
                .catch(() => alert("Verifique os dados inseridos"));
        },
        generateTicket() {
            if(this.currentUser.payment_method === 'bolbradesco' && !this.currentUser.isRegular) {
                this.ticketLoading = true;
                const self = this;

                axios
                    .post("/api/payment", {
                        "is_ticket": true
                    })
                    .then((res) => {self.ticket_url = res.data.url; return res.data.url})
                    .catch(() => alert("Falha ao gerar o boleto"))
                    .finally(() => {self.ticketLoading = false});
            }
        },
        creditCardTransaction() {
            if (!this.mercadoPago) {
                alert('Pagamento com cartao indisponivel no momento.');
                return;
            }

            const cpf_cnpf = this.inputs.identificationNumber.replace(/\D/g,'');
            const cardNumber = this.inputs.cardNumber.replace(/\D/g,'');
            const cardExpirationMonth = ("00" + (this.inputs.expireDate.month + 1).toString()).slice(-2);

            if(!(cardNumber &&  this.inputs.cardholderName && this.inputs.identificationNumber &&
                this.inputs.expireDate && this.inputs.cvv)) {
                alert("Há dados não preenchidos");
                return;
            }

            if(![11,14].includes(cpf_cnpf.length)) {
                alert("Verifique o CPF/CNPJ");
                return;
            }

            this.mercadoPago
                .createCardToken({
                    cardNumber: cardNumber,
                    cardholderName: this.inputs.cardholderName,
                    identificationNumber: cpf_cnpf,
                    identificationType: cpf_cnpf.length === 11 ? 'CPF' : 'CNPJ',
                    securityCode: this.inputs.cvv,
                    cardExpirationMonth: cardExpirationMonth,
                    cardExpirationYear: this.inputs.expireDate.year.toString(),
                })
                .then(({ id }) => {
                    axios.post("/api/payment", {
                        "is_ticket": false,
                        "card_token": id,
                        "cardholderName": this.inputs.cardholderName,
                        "identificationNumber": cpf_cnpf
                    })
                    .then(() => {
                        this.currentUserStore.refresh();
                        alert("Pagamento Realizado com sucesso!");
                        this.$router.push({name: 'home'});
                        this.$forceUpdate();
                    })
                    .catch(error => alert(error.response ? error.response.data?.error : error));
                })
                .catch((e) => {alert("Verifique os dados inseridos");console.error(e)});
        }
    },
    computed: {
        hasChanged() {
            return this.currentUser.payment_method !== this.inputs.paymentMethod;
        }
    }
}
</script>

<style scoped>
    .form-container {
        width: calc(8/12 * 100%);
    }
</style>
