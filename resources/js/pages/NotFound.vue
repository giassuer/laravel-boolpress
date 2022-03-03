<template>
    <section>
        <div class="container">
            <h1>Ricerca fallita</h1>
            <router-link class="nav-link" :to="{ name: 'blog' }">Torna al blog</router-link>
        </div>
    </section>
</template>

<script>
export default {
    name: 'NotFound'
}
</script>