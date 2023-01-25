<template>
    <div class="fm-body">
        <div class="fm-content alert fade show text-center" :class="status" role="alert">
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
