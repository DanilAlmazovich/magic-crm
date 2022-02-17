<template>
    <div>
        <h2 class="text-4xl text-gray-900 font-semibold mb-5">Комментарии курьеру</h2>
        <div class="pl-10 mb-6">
            <div>
                <table class="w-full">
                    <tr>
                        <th class="title"></th>
                        <th class="text-base text-left text-gray-200 pr-1">Заведение</th>
                        <th class="text-base text-left text-gray-200 pr-1">Дата</th>
                        <th class="text-base text-left text-gray-200 pr-1">Комментарий</th>
                    </tr>
                    <tr v-for="comment in comments" :key="comment.id">
                        <td class="border-l-2 border-orange-400"></td>
                        <td class="text-lg text-left text-gray-900 leading-none pl-2 py-3 border-b border-gray-150">{{ comment.branch ? comment.branch.partner.name : '' }}</td>
                        <td class="text-lg text-left text-gray-900 border-b border-gray-150 leading-none py-3">{{ moment(comment.created_at).format('YYYY-MM-DD') }}</td>
                        <td class="text-lg text-left text-gray-900 border-b border-gray-150 leading-none py-3">{{ comment.comment }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['comments']
}
</script>
