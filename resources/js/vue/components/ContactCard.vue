<template>
    <div class="contact-card" :class="[contact.favorite ? 'favorite' : '']">
        <h4>{{contact.name}}</h4>
        <p>Teléfono: {{contact.phone}}</p>
        <p>Email: <a href="#">{{contact.email}}</a></p>
         <input type="hidden" :value="contact.id">
        <div class="buttons-container">
            <button @click="updateContact">Actualizar</button>
            <button class="red-hover" @click="deleteContact">Eliminar</button>
        </div>

        <!-- Fixed Elements -->
        <transition name="fade">
            <confirmation-modal 
            v-if="showConfirmationModal"
            @actionConfirmed="setDeleteConfirmation"
            @actionCanceled="hideConfirmationModal">
            </confirmation-modal>
        </transition>


    </div>
</template>

<script>
import ConfirmationModal from './ConfirmationModal.vue';

export default {
    data() {
        return {
            showConfirmationModal: false,
            deleteConfirmation: false
        };
    },
    components: {
        ConfirmationModal
    },
    props: ['contact'],
    methods: {
        updateContact() {
            this.$emit('updateContact' , this.contact);
        },
        async deleteContact(showConfirmationModal = true) {
            this.showConfirmationModal = showConfirmationModal;
            if (this.deleteConfirmation) {
                const token = localStorage.getItem('token');
                const response = await fetch(`/api/contacts/${this.contact.id}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    this.$emit('contactDeleted', this.contact);
                } else {
                    console.log('Ha occurrido un error en el servidor');
                }
            }

            
        },
        setDeleteConfirmation() {
            this.deleteConfirmation = true;
            this.deleteContact(false);
            this.deleteConfirmation = false;
        },
        hideConfirmationModal() {
            this.showConfirmationModal = false;
        }
    }
}
</script>

<style scoped>
.contact-card {
    width: 100%;
    border: 2px solid rgb(59, 59, 59);;
    padding: 10px;
}

.contact-card h4 {
    text-transform: capitalize;
}

.favorite {
    background-color: rgb(59, 59, 59);
    color: white;
}

.favorite button {
    border-color: white;
    color: white;
}

.favorite button:hover {
    background-color: white;
    color: rgb(59, 59, 59);
    border-color: rgb(59, 59, 59);
}

h4 {
    text-align: center;
    margin-bottom: 5px;
}
p {
    margin-bottom: 3px;
}

</style> 