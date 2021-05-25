<template>
    <div class="contacts-list">

        <h2 v-if="!haveAnyContacts">Lo sentimos, no tienes contactos en tu lista</h2>

        <div class="contacts-container">
            <transition-group name="card-transition">
                <contact-card 
                v-for="contact in contacts" :key="contact.id"
                :contact="contact"
                @updateContact="updateContact"
                @contactDeleted="deleteContact">
                </contact-card>
            </transition-group>
        </div>

        <!-- Fixed Elements -->

        <transition name="fade">
            <contact-form 
            v-if="showContactForm"
            :mode="formMode"
            :contact="contact"
            @formClosed="reflectFormChanges"
            @contactCreated="showNewContact"
            @formCanceled="cancelForm"
            @closeForm="showContactForm = false"> 
            </contact-form>
        </transition>
        <button class="btn-new" @click="addContact">Nuevo</button>
    </div>
</template>

<script>
import ContactCard from './ContactCard.vue';
import ContactForm from './ContactForm.vue';


export default {
    components: {
        ContactCard,
        ContactForm
    },
    data() {
        return {
            contacts: [],
            showContactForm: false,
            formMode: 'add',
            contact: {
                name: '',
                phone: '',
                email: '',
                favorite: false,
                id: null,
                
            },
            haveAnyContacts: true
        }
    },
    watch: {
        contacts: {
            handler(val) {
                if (val.length === 0) {
                    this.haveAnyContacts = false;
                    console.log('No contacts', val)
                } else {
                    this.haveAnyContacts = true;
                    console.log('Cpntacts', val);
                }
            },
            deep: true
        }
    },
    created() {
        this.getAllContacts();
    },
    methods: {
        async getAllContacts() {

            const token = localStorage.getItem('token');
            const response = await fetch('/api/contacts', {
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            
            if (response.ok) {
                const responseData = await response.json();
                this.contacts = responseData;
            } else {
                console.log('Ha occurrido un error en el servidor');
            }
        },
        addContact() {
            this.contact = {
                name: '',
                phone: '',
                email: '',
                favorite: false,
                id: null 
            };
            this.formMode = 'add';
            this.showContactForm = true;
        },
        updateContact(contact) {
            this.formMode = 'update';
            this.contact = contact;
            this.showContactForm = true;
        },
        deleteContact(contact) {
            const index = this.contacts.findIndex(el => el.id === contact.id);
            this.contacts.splice(index, 1);
        },
        reflectFormChanges(contact, isNewContact) {
            this.showContactForm = false;
            if (contact && isNewContact) {
                this.contacts.push(contact);
            }
        },
        showNewContact(contact) {
            this.contacts.push(contact);
        },
        cancelForm(contact) {
            if (contact.id) {
                const index = this.contacts.findIndex(el => el.id === contact.id)
                this.contacts[index].name = contact.name;
                this.contacts[index].phone = contact.phone;
                this.contacts[index].email = contact.email;
                this.contacts[index].favorite = contact.favorite;
            }
            this.showContactForm = false;
        }
    }
}
</script>

<style>
.contacts-list {
    width: 80%;
    margin: 0 auto;
}

.contacts-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.btn-new {
    position: fixed;
    bottom: 0;
    right: 0;
    margin: 50px;
    font-size: 20px;
    font-weight: bold;
    background-color: rgba(52, 52, 52, 1);
    color: white;
}

.btn-new:hover {
    background-color: white;
    color: rgba(52, 52, 52, 1);
    border: 2px solid rgba(52, 52, 52, 1);
}

/* Responsive */

@media (max-width: 600px) {
    .contacts-container {
        display: flex;
        flex-direction: column;
    }
    .btn-new {
        margin: 50px 15px;
        background-color: rgba(52, 52, 52, 1);
        color: white;
    }
}

/* Transitions */
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.5);
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.card-transition-enter-to,
.card-transition-leave-from {
    opacity: 1;
    transform: scale(1);
}

.card-transition-enter-from,
.card-transition-leave-to {
    opacity: 0;
    transform: scale(0.5);
}

.card-transition-enter-active {
    transition: all 0.3s ease;
}


.card-transition-leave-active {
    transition: all 0.3s ease;
}

.card-transition-enter-to,
.card-transition-leave-from {
    opacity: 1;
    transform: scale(1);
}

</style>
