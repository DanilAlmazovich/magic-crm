<template>
    <span v-html="require(`!html-loader!../../svg/${name}.svg`)" />
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true
            }
        },
    }
</script>
