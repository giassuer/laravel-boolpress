<template>
    <section>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Boolpress</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li v-for="(link, index) in menuLinks" :key="index" class="nav-item">
                            <router-link class="nav-link" :to="{ name: link.name }">{{ link.label }}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
       </nav>
    </section>
</template>

<script>
export default {
    name: 'Header',

    data: function(){
        return {
            menuLinks: [
                {
                    name: 'home',
                    label: 'Home'
                },

                {
                    name: 'about',
                    label: 'About'
                },

                {
                    name: 'blog',
                    label: 'Blog'
                }
            ]
        }
    }
}
</script>