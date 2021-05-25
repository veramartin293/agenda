<template>
    <div class="backdrop" @click="cancelForm">
        <form @submit.prevent @click.stop>
            <h2 v-if="mode === 'add'">Agregar contacto</h2>
            <h2 v-else-if="mode === 'update'" >Editar contacto</h2>

            <input type="text" placeholder="Nombre..." v-model="contact.name" @blur="checkValidation(contact.name, 'name')">
            <p class="error form-error" v-if="formValidationErrors.name">{{formValidationErrors.name}}</p>

            <input type="number" placeholder="Teléfono..." v-model="contact.phone" @blur="checkValidation(contact.phone, 'phone')">
            <p class="error form-error" v-if="formValidationErrors.phone">{{formValidationErrors.phone}}</p>

            <input type="email" placeholder="Email..." v-model="contact.email" @blur="checkValidation(contact.email, 'email')">
            <p class="error form-error" v-if="formValidationErrors.email">{{formValidationErrors.email}}</p>

            <div class="contact-field">
                <label for="favorite">Favorito</label>
                <input type="checkbox" id="favorite" v-model="contact.favorite">
                <p class="error form-error" v-if="formValidationErrors.favorite">{{formValidationErrors.favorite}}</p>
            </div>

            <input type="hidden" v-model="contact.id">

            <p class="error">{{error}}</p>

            <div class="buttons-container">
                <button v-if="mode === 'add'" @click="addContact">Guardar</button>
                <button v-if="mode === 'update'" @click="updateContact">Guardar</button>
                <button class="red-hover" @click="cancelForm">Cancelar</button>
            </div>
        </form>
    </div>


</template>

<script>
export default {
    props: ['mode', 'contact'],
    data() {
        return {
            contactBeforeChanges: {
                name: this.contact.name,
                phone: this.contact.phone,
                email: this.contact.email,
                favorite: this.contact.favorite,
                id: this.contact.id
            },
            error: '',
            formValidationErrors: {
                name: '',
                phone: '',
                email: '',
                favorite: ''
            }
        }
    },
    methods: {
        async addContact() {
            if (!this.validateForm()) {
                return;
            }

            this.$emit('closeForm');

            const token = localStorage.getItem('token');
            const response = await fetch('/api/contacts', {
                method: 'POST',
                body: JSON.stringify({
                    name: this.contact.name,
                    phone: this.contact.phone,
                    email: this.contact.email,
                    favorite: this.contact.favorite
                }),
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            });

            if (response.ok) {
                const createdContact = await response.json();
                this.closeForm(createdContact, true);
            } else {
                const responseData = await response.json();
                for (const key in responseData.errors) {
                    this.formValidationErrors[key] = responseData.errors[key][0];              }
            }
        },
        async updateContact() {
            if (!this.validateForm()) {
                return;
            }

            this.$emit('closeForm');
            
            const token = localStorage.getItem('token');
            const response = await fetch(`/api/contacts/${this.contact.id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    name: this.contact.name,
                    phone: this.contact.phone,
                    email: this.contact.email,
                    favorite: this.contact.favorite
                }),
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const updatedContact = await response.json();
                this.closeForm(updatedContact, false);
            }
        },
        closeForm(contact = null, isNewContact = false) {
            this.$emit('formClosed', contact, isNewContact);
        },
        cancelForm() {
            this.$emit('formCanceled', this.contactBeforeChanges);
        },
        checkValidation(field, fieldString) {
            if (field) {
                this.formValidationErrors[fieldString] = '';
            }
        },
        validateForm() {
            let formValidated = true;
            if (!this.contact.name) {
                this.formValidationErrors.name = 'El nombre es obligatorio';
                formValidated = false;
            } else {
                this.formValidationErrors.name = '';
            }
            if (!this.contact.email) {
                this.formValidationErrors.email = 'El correo electrónico es obligatorio';
                formValidated = false;
            } else {
                this.formValidationErrors.email = '';
            }
            if (!this.contact.phone) {
                this.formValidationErrors.phone = 'El número de teléfono es obligaorio';
                formValidated = false;
            } else {
                this.formValidationErrors.phone = '';
            }

            return formValidated;
        }
    }
}
</script>

<style>
form {
    background-color: white;
    width: 400px;
    padding: 40px;
    box-shadow: 0px 0px 21px -3px rgba(52,52,52,1);
}

form h2 {
    padding: 15px 0px;
    text-align: center;
}

input:not([type="checkbox"]) {
    height: 40px;
    margin-bottom: 10px;
    width: 100%;
}

.contact-field {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

#favorite {
    width: 40px;
    height: 20px;
}

/* Responsive */

@media (max-width: 600px) {
    form {
        width: 100%;
    }
}


</style>