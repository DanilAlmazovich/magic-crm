<template>
    <div class="w-1/4 pr-4">
        <div class="flex justify-between mb-4">
            <span class="font-medium text-gray-900 text-lg">Фото</span>
            <label for="file" class="text-base text-orange-400 cursor-pointer">Выбрать файл</label>
            <input id="file" @change="getFileName" ref="fileInput" type="file" class="hidden">
        </div>
        <div class="operator-photo w-full rounded-xl overflow-hidden h-40 border-2 border-gray-150">
            <img v-show="imageSrc" :src="imageSrc" alt="" class="w-full h-full object-cover">
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            src: {
                type: String,
                default: null
            }
        },
        data() {
            return {
                imageSrc: this.src
            }
        },
        methods: {
            getFileName(event) {
                let input = event.target
                if (input.files && input.files[0]) {
                    let reader = new FileReader()
                    let that = this;

                    reader.onload = (e) => {
                        that.imageSrc = e.target.result
                    }

                    reader.readAsDataURL(input.files[0])

                    this.$emit('change', input.files[0])
                }
            }
        }
    }
</script>
<style scoped>
    .operator-photo {
        background: url("/images/picture-icon.png") center center no-repeat;
    }
</style>
