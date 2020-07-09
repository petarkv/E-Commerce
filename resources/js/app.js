/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var app = new Vue({
    el: '#app',
    data:{
        testmsg:'Post Data',
        responsemsg:'',
        search:'',
        enquiries:[],
    },
    ready:function(){
        this.created();
    },
    created(){
        axios.get('/admin/get-enquiries')
        .then(response=> {
            this.enquiries = response.data;
        })
        .catch(function (error){
            console.log(error);
        });
    },
    computed:{
        filteredEnquiries(){
            return this.enquiries.filter(enquiry => {
                return enquiry.name.toLowerCase().includes(this.search.toLowerCase())
            })
        }
    },
    methods:{
        addPost() {
            axios.post('/page/post', {name: this.name, email: this.email, subject: this.subject, message: this.message})
                // .then(post => this.$emit('completed', name));
                .then(function (response){
                    // alert(response.data);
                   app.responsemsg = response.data;
                }) 
        }

    }
});
