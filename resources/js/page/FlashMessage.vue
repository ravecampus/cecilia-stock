<template>
    <div class="fm-body">
        <div class="fm-content alert alert-dismissible fade show text-center bg-orange" v-bind:class="status" role="alert">
            <strong>{{message}}</strong> 
        </div>
    </div>
</template>
<script>
export default {
    props:['message', 'status'],
    mouted(){
    
    },
}
</script>
